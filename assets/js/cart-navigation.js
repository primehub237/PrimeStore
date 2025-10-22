(function() {
    'use strict';

    // ============================================
    // STATE MANAGEMENT
    // ============================================
    let currentStep = 1;
    const totalSteps = 3;

    // ============================================
    // DOM ELEMENTS
    // ============================================
    const stepContents = document.querySelectorAll('.step-content');
    const stepIndicators = document.querySelectorAll('.progress-steps .step');
    const stepLines = document.querySelectorAll('.step-line');

    // ============================================
    // INITIALIZE
    // ============================================
    function init() {
        const urlParams = new URLSearchParams(window.location.search);
        const stepParam = urlParams.get('step');
        if (stepParam) {
            currentStep = parseInt(stepParam);
            if (currentStep < 1 || currentStep > totalSteps) {
                currentStep = 1;
            }
        }

        setupInitialNavigationButtons();
        updateStepDisplay();
        loadStepContent();
    }

    // ============================================
    // SETUP INITIAL NAVIGATION BUTTONS
    // ============================================
    function setupInitialNavigationButtons() {
        const initialButtons = [
            { id: 'proceedToAddress', step: 2, validator: validateCart }
        ];

        initialButtons.forEach(button => {
            const element = document.getElementById(button.id);
            if (element) {
                console.log(`Attaching initial listener to ${button.id}`);
                element.addEventListener('click', () => {
                    if (button.validator && !button.validator()) {
                        console.warn(`Validation failed for ${button.id}`);
                        return;
                    }
                    navigateToStep(button.step);
                });
            } else {
                console.warn(`Initial button ${button.id} not found in DOM`);
            }
        });

        stepIndicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                const targetStep = index + 1;
                if (targetStep <= currentStep || indicator.classList.contains('completed')) {
                    navigateToStep(targetStep);
                }
            });
        });
    }

    // ============================================
    // SETUP DYNAMIC NAVIGATION BUTTONS
    // ============================================
    function setupDynamicNavigationButtons() {
        const dynamicButtons = [
            { id: 'backToCart', step: 1 },
            { id: 'proceedToPayment', step: 3, validator: validateAddress },
            { id: 'backToAddress', step: 2 }
        ];

        dynamicButtons.forEach(button => {
            const element = document.getElementById(button.id);
            if (element) {
                console.log(`Attaching dynamic listener to ${button.id}`);
                element.addEventListener('click', () => {
                    if (button.validator && !button.validator()) {
                        console.warn(`Validation failed for ${button.id}`);
                        return;
                    }
                    navigateToStep(button.step);
                });
            } else {
                console.warn(`Dynamic button ${button.id} not found in DOM`);
            }
        });
    }

    // ============================================
    // NAVIGATE TO STEP
    // ============================================
    function navigateToStep(step) {
        if (step < 1 || step > totalSteps) {
            console.error(`Invalid step: ${step}`);
            return;
        }

        console.log(`Navigating to step ${step}`);
        const direction = step > currentStep ? 'forward' : 'backward';
        const previousStep = currentStep;
        currentStep = step;

        fetch('/cart/update-session.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cart_step=${step}`
        }).catch(error => console.error('Error updating session:', error));

        const newUrl = new URL(window.location);
        newUrl.searchParams.set('step', step);
        window.history.pushState({step: step}, '', newUrl);

        animateStepTransition(previousStep, step, direction);
        updateStepIndicators();
        loadStepContent();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ============================================
    // ANIMATE STEP TRANSITION
    // ============================================
    function animateStepTransition(fromStep, toStep, direction) {
        const fromContent = document.getElementById(`step-${fromStep}`);
        const toContent = document.getElementById(`step-${toStep}`);

        if (!fromContent || !toContent) {
            console.error(`Content not found for steps: from ${fromStep}, to ${toStep}`);
            return;
        }

        if (direction === 'forward') {
            fromContent.style.animation = 'slideOutLeft 0.4s ease-in-out';
            toContent.style.animation = 'slideInRight 0.4s ease-in-out';
        } else {
            fromContent.style.animation = 'slideOutRight 0.4s ease-in-out';
            toContent.style.animation = 'slideInLeft 0.4s ease-in-out';
        }

        setTimeout(() => {
            fromContent.classList.remove('active');
            toContent.classList.add('active');
            fromContent.style.animation = '';
            toContent.style.animation = '';
            setTimeout(setupDynamicNavigationButtons, 100); // Delayed attachment
        }, 400);
    }

    // ============================================
    // UPDATE STEP INDICATORS
    // ============================================
    function updateStepIndicators() {
        stepIndicators.forEach((indicator, index) => {
            const stepNumber = index + 1;
            if (stepNumber < currentStep) {
                indicator.classList.add('completed');
                indicator.classList.remove('active');
            } else if (stepNumber === currentStep) {
                indicator.classList.add('active');
                indicator.classList.remove('completed');
            } else {
                indicator.classList.remove('active', 'completed');
            }
        });

        stepLines.forEach((line, index) => {
            if (index < currentStep - 1) {
                line.classList.add('completed');
            } else {
                line.classList.remove('completed');
            }
        });
    }

    // ============================================
    // UPDATE STEP DISPLAY
    // ============================================
    function updateStepDisplay() {
        stepContents.forEach((content, index) => {
            if (index + 1 === currentStep) {
                content.classList.add('active');
            } else {
                content.classList.remove('active');
            }
        });
    }

    // ============================================
    // LOAD STEP CONTENT
    // ============================================
    function loadStepContent() {
        const stepContent = document.getElementById(`step-${currentStep}`);
        
        if (stepContent && stepContent.children.length === 0) {
            let pageUrl = '';
            switch(currentStep) {
                case 2:
                    pageUrl = '../shop/address.php';
                    break;
                case 3:
                    pageUrl = '../shop/payment.php';
                    break;
                default:
                    return;
            }

            console.log(`Loading content for step ${currentStep} from ${pageUrl}`);
            stepContent.innerHTML = `
                <div class="flex items-center justify-center py-20">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
                        <p class="text-gray-600">Loading...</p>
                    </div>
                </div>
            `;

            fetch(pageUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    stepContent.innerHTML = html;
                    setTimeout(setupDynamicNavigationButtons, 100); // Delayed attachment
                })
                .catch(error => {
                    console.error(`Error loading step ${currentStep} from ${pageUrl}:`, error);
                    stepContent.innerHTML = `
                        <div class="flex items-center justify-center py-20">
                            <div class="text-center">
                                <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                                <p class="text-gray-600">Error loading content: ${error.message}. Please try again.</p>
                                <button onclick="location.reload()" class="mt-4 px-6 py-2 bg-primary text-secondary font-semibold rounded-lg hover:bg-primary-hover">
                                    Reload Page
                                </button>
                            </div>
                        </div>
                    `;
                });
        }
    }

    // ============================================
    // VALIDATION FUNCTIONS
    // ============================================
    function validateCart() {
        const selectedItems = document.querySelectorAll('.item-checkbox:checked');
        if (selectedItems.length === 0) {
            console.error('Validation failed: No items selected in cart');
            showToast('Please select at least one item to proceed to address', 'error');
            return false;
        }
        return true;
    }

    function validateAddress() {
        const selectedAddress = document.querySelector('input[name="selectedAddress"]:checked');
        if (!selectedAddress) {
            console.error('Validation failed: No address selected');
            showToast('Please select a delivery address to proceed to payment', 'error');
            return false;
        }
        return true;
    }

    // ============================================
    // UTILITY: SHOW TOAST
    // ============================================
    function showToast(message, type = 'info') {
        const existingToast = document.getElementById('toast');
        if (existingToast) {
            const toastMessage = existingToast.querySelector('#toastMessage');
            if (toastMessage) {
                toastMessage.textContent = message;
            }
            
            existingToast.className = `toast ${type}`;
            existingToast.style.opacity = '1';
            existingToast.style.transform = 'translateY(0)';
            
            setTimeout(() => {
                existingToast.style.opacity = '0';
                existingToast.style.transform = 'translateY(20px)';
            }, 3000);
        } else {
            console.warn('Toast element not found');
        }
    }

    // ============================================
    // BROWSER BACK/FORWARD HANDLING
    // ============================================
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.step) {
            currentStep = event.state.step;
            updateStepDisplay();
            updateStepIndicators();
            loadStepContent();
        }
    });

    // ============================================
    // INITIALIZE ON DOM READY
    // ============================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();