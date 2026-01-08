// Get order ID from URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    
    if (orderId) {
        document.getElementById('orderId').textContent = orderId;
        
        // Set download links
        document.getElementById('downloadPDF').href = `storage/exports/${orderId}.html`;
        document.getElementById('downloadImage').href = `storage/exports/${orderId}-image.html`;
        
        // Note: In production, these would be actual PDF and image files
        // For now, they're HTML files that can be converted
    } else {
        // Redirect if no order ID
        window.location.href = 'index.html';
    }
});
