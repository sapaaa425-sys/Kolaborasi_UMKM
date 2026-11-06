document.addEventListener('DOMContentLoaded', () => {
  const orderForm = document.querySelector('#orderForm');
  if (orderForm) {
    orderForm.addEventListener('submit', (event) => {
      const jumlah = Number(document.querySelector('#jumlah')?.value || 0);
      const telepon = document.querySelector('#telepon')?.value.trim() || '';
      if (jumlah < 1 || jumlah > 50) {
        event.preventDefault();
        alert('Jumlah pesanan harus antara 1 dan 50.');
      } else if (telepon.length < 8) {
        event.preventDefault();
        alert('Periksa kembali nomor WhatsApp Anda.');
      }
    });
  }
});
