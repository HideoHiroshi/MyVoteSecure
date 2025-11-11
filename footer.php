</main> <!-- Close main container -->

    <!-- Footer -->
    <footer class="site-footer">
        <p>
            <i class="fas fa-copyright"></i> Hakcipta 2025-2026: <b>MyVoteSecure</b>
            <br>
            <small>Undian Online, Kelab Bowling SMK Kuning Padi</small>
        </p>
    </footer>

    <!-- JavaScript for interactivity -->
    <script>
        // Professional Custom Modal Function
        function showModal(type, title, message, redirectUrl = null, autoClose = false) {
            // Create modal overlay
            const overlay = document.createElement('div');
            overlay.className = 'custom-modal-overlay';
            
            // Determine icon based on type
            let icon = '';
            let iconClass = '';
            let buttonClass = '';
            
            if (type === 'success') {
                icon = '<i class="fas fa-check-circle"></i>';
                iconClass = 'success';
                buttonClass = 'success-btn';
            } else if (type === 'error') {
                icon = '<i class="fas fa-times-circle"></i>';
                iconClass = 'error';
                buttonClass = 'danger-btn';
            } else if (type === 'warning') {
                icon = '<i class="fas fa-exclamation-triangle"></i>';
                iconClass = 'warning';
                buttonClass = 'warning-btn';
            }
            
            // Create modal content
            overlay.innerHTML = `
                <div class="custom-modal">
                    <div class="modal-icon ${iconClass}">
                        ${icon}
                    </div>
                    <h2 class="modal-title">${title}</h2>
                    <p class="modal-message">${message}</p>
                    <button class="modal-button ${buttonClass}" onclick="closeModal(${redirectUrl ? `'${redirectUrl}'` : 'null'})">
                        <i class="fas fa-check"></i> OK
                    </button>
                </div>
            `;
            
            // Add to body
            document.body.appendChild(overlay);
            
            // Auto close if specified
            if (autoClose) {
                setTimeout(() => {
                    closeModal(redirectUrl);
                }, 2000);
            }
            
            // Close on overlay click
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeModal(redirectUrl);
                }
            });
        }
        
        // Close Modal Function
        function closeModal(redirectUrl = null) {
            const overlay = document.querySelector('.custom-modal-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    overlay.remove();
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    }
                }, 300);
            }
        }

        // Password toggle function
        function togglePassword(inputId, button) {
            const passwordInput = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Font size control
        function ubahsaiz(gandaan) {
            var saiz = document.getElementById("saiz");
            if (gandaan === 2) {
                saiz.style.fontSize = "1em";
            } else {
                var currentSize = parseFloat(window.getComputedStyle(saiz).fontSize);
                var newSize = currentSize + (gandaan * 2);
                saiz.style.fontSize = newSize + "px";
            }
        }

        // Add animation to cards on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card, .candidate-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * 100);
            });
        });

        // Confirm delete actions
        function confirmDelete(message) {
            return confirm(message || 'Anda pasti ingin memadam data ini?');
        }

        // Handle radio button selection with visual feedback
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio') {
                // Remove selected class from all cards in the same group
                const name = e.target.name;
                const cards = document.querySelectorAll(`input[name="${name}"]`);
                cards.forEach(radio => {
                    const card = radio.closest('.candidate-card');
                    if (card) {
                        card.classList.remove('selected');
                    }
                });
                
                // Add selected class to chosen card
                const selectedCard = e.target.closest('.candidate-card');
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                }
            }
        });

        // Form validation enhancement
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#ef4444';
                    } else {
                        field.style.borderColor = '#e5e7eb';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Sila lengkapkan semua medan yang diperlukan');
                }
            });
        });
    </script>
</body>
</html>