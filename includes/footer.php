</div> </main> </div> <script>
    // This looks for any <i data-lucide="..."></i> tags and replaces them with SVGs
    lucide.createIcons();
</script>

<script>
    // Close notifications automatically if they exist
    document.addEventListener('DOMContentLoaded', () => {
        const alerts = document.querySelectorAll('#success-toast, #delete-toast');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.6s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 600);
            }, 3000);
        });
    });
</script>

<?php if (file_exists('../assets/js/main.js')): ?>
    <script src="../assets/js/main.js"></script>
<?php endif; ?>

</body>
</html>