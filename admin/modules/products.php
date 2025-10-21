<?php
$page_title = "Products Management";
require_once '../includes/header.php';

// Handle product actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    if ($_GET['action'] === 'toggle_status') {
        $stmt = $pdo->prepare("UPDATE products SET status = IF(status = 'active', 'inactive', 'active') WHERE product_id = ?");
        $stmt->execute([$product_id]);
        log_admin_action("Product status toggled", "Product ID: $product_id");
        header('Location: products.php?success=Product status updated');
        exit();
    } elseif ($_GET['action'] === 'delete' && isset($_GET['confirm'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        log_admin_action("Product deleted", "Product ID: $product_id");
        header('Location: products.php?success=Product deleted successfully');
        exit();
    }
}

// Handle bulk actions
if (isset($_POST['bulk_action']) && isset($_POST['selected_products'])) {
    $product_ids = $_POST['selected_products'];
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    
    if ($_POST['bulk_action'] === 'activate') {
        $stmt = $pdo->prepare("UPDATE products SET status = 'active' WHERE product_id IN ($placeholders)");
        $stmt->execute($product_ids);
        log_admin_action("Bulk product activation", count($product_ids) . " products activated");
    } elseif ($_POST['bulk_action'] === 'deactivate') {
        $stmt = $pdo->prepare("UPDATE products SET status = 'inactive' WHERE product_id IN ($placeholders)");
        $stmt->execute($product_ids);
        log_admin_action("Bulk product deactivation", count($product_ids) . " products deactivated");
    } elseif ($_POST['bulk_action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id IN ($placeholders)");
        $stmt->execute($product_ids);
        log_admin_action("Bulk product deletion", count($product_ids) . " products deleted");
    }
    
    header('Location: products.php?success=Bulk action completed');
    exit();
}

// Fetch products with filters
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$type_filter = $_GET['type'] ?? '';
$status_filter = $_GET['status'] ?? '';

$query = "
    SELECT p.*, c.category_name, u.first_name, u.last_name, u.username,
           (SELECT COUNT(*) FROM order_items WHERE product_id = p.product_id) as total_orders
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.category_id 
    LEFT JOIN users u ON p.user_id = u.user_id 
    WHERE 1=1
";

$params = [];
if (!empty($search)) {
    $query .= " AND (p.product_name LIKE ? OR p.description LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term]);
}

if (!empty($category_filter)) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_filter;
}

if (!empty($type_filter)) {
    $query .= " AND p.product_type = ?";
    $params[] = $type_filter;
}

if (!empty($status_filter)) {
    $query .= " AND p.status = ?";
    $params[] = $status_filter;
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories for filter
$stmt = $pdo->query("SELECT * FROM categories ORDER BY category_name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistics
$total_products = count($products);
$aliexpress_products = array_filter($products, fn($p) => $p['product_type'] === 'aliexpress');
$vendor_products = array_filter($products, fn($p) => $p['product_type'] === 'vendor');
$active_products = array_filter($products, fn($p) => $p['status'] === 'active');
$total_orders = array_sum(array_column($products, 'total_orders'));
?>

<!-- Statistics Cards -->
<div class="row mb-4 animate-fade-in-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_products; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-box"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($aliexpress_products); ?></div>
                    <div class="stat-label">AliExpress Products</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-globe"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($vendor_products); ?></div>
                    <div class="stat-label">Vendor Products</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_orders; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cart"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Bulk Actions -->
<div class="card mb-4 animate-slide-in-left">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-filter me-2"></i>Filters & Bulk Actions
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search products..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>"
                        <?php echo $category_filter == $category['category_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="vendor" <?php echo $type_filter === 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                    <option value="aliexpress" <?php echo $type_filter === 'aliexpress' ? 'selected' : ''; ?>>AliExpress
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="products.php" class="btn btn-secondary">Reset</a>
                <a href="?action=add" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            </div>
        </form>

        <!-- Bulk Actions -->
        <form method="POST" class="mt-3" id="bulkActionForm">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <select name="bulk_action" class="form-select" id="bulkAction">
                        <option value="">Bulk Actions</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary" id="applyBulkAction">Apply</button>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">
                        Select products using the checkboxes below and choose an action to apply to all selected items.
                    </small>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card animate-slide-in-left">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-box-seam me-2"></i>All Products (<?php echo $total_products; ?>)
        </h5>
        <div>
            <span class="badge bg-success me-2"><?php echo count($active_products); ?> Active</span>
            <span class="badge bg-secondary"><?php echo $total_products - count($active_products); ?> Inactive</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="30">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Vendor</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Orders</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_products[]"
                                value="<?php echo $product['product_id']; ?>" class="product-checkbox">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="../uploads/products/<?php echo htmlspecialchars($product['image'] ?? 'default.png'); ?>"
                                    alt="Product Image" class="rounded me-2" width="50" height="50"
                                    style="object-fit: cover;">
                                <div>
                                    <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                                    <?php if ($product['product_type'] === 'aliexpress'): ?>
                                    <br><small class="text-muted">AliExpress ID:
                                        <?php echo $product['aliexpress_product_id']; ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                        <td>
                            <?php if ($product['user_id']): ?>
                            <div>
                                <strong><?php echo htmlspecialchars($product['first_name'] . ' ' . $product['last_name']); ?></strong>
                                <br><small
                                    class="text-muted">@<?php echo htmlspecialchars($product['username']); ?></small>
                            </div>
                            <?php else: ?>
                            <span class="text-muted">Platform</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span
                                class="badge bg-<?php echo $product['product_type'] === 'aliexpress' ? 'info' : 'primary'; ?>">
                                <i
                                    class="bi bi-<?php echo $product['product_type'] === 'aliexpress' ? 'globe' : 'shop'; ?> me-1"></i>
                                <?php echo ucfirst($product['product_type']); ?>
                            </span>
                        </td>
                        <td>
                            <span
                                class="fw-bold text-success">$<?php echo number_format($product['price'], 2); ?></span>
                        </td>
                        <td>
                            <span
                                class="badge bg-<?php echo $product['stock'] > 10 ? 'success' : ($product['stock'] > 0 ? 'warning' : 'danger'); ?>">
                                <?php echo $product['stock']; ?> in stock
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $product['total_orders']; ?> orders</span>
                        </td>
                        <td>
                            <span
                                class="badge bg-<?php echo $product['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($product['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($product['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="product_details.php?id=<?php echo $product['product_id']; ?>"
                                    class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="?action=toggle_status&id=<?php echo $product['product_id']; ?>"
                                    class="btn btn-outline-<?php echo $product['status'] === 'active' ? 'warning' : 'success'; ?>"
                                    data-bs-toggle="tooltip"
                                    title="<?php echo $product['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>"
                                    onclick="return confirm('Are you sure?')">
                                    <i
                                        class="bi bi-<?php echo $product['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                </a>
                                <?php if ($product['product_type'] === 'aliexpress' && $product['aliexpress_url']): ?>
                                <a href="<?php echo htmlspecialchars($product['aliexpress_url']); ?>" target="_blank"
                                    class="btn btn-outline-info" data-bs-toggle="tooltip" title="View on AliExpress">
                                    <i class="bi bi-link"></i>
                                </a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $product['product_id']; ?>&confirm=1"
                                    class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Delete Product"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($products)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No products found</h4>
            <p class="text-muted">Try adjusting your search filters or add new products.</p>
            <a href="?action=add" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle"></i> Add First Product
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Bulk actions functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const bulkActionForm = document.getElementById('bulkActionForm');
    const applyBulkAction = document.getElementById('applyBulkAction');
    const bulkAction = document.getElementById('bulkAction');

    // Select all checkboxes
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Bulk action form submission
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedProducts = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb
            .value);
            const action = bulkAction.value;

            if (selectedProducts.length === 0) {
                alert('Please select at least one product.');
                return;
            }

            if (!action) {
                alert('Please select a bulk action.');
                return;
            }

            if (action === 'delete' && !confirm(
                    'Are you sure you want to delete the selected products? This action cannot be undone.'
                    )) {
                return;
            }

            // Add selected products to form
            selectedProducts.forEach(productId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_products[]';
                input.value = productId;
                bulkActionForm.appendChild(input);
            });

            bulkActionForm.submit();
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>