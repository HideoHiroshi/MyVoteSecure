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