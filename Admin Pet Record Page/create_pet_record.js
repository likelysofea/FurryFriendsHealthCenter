function goBackCreatePetRecord() {
    window.history.back();
}

document.addEventListener("DOMContentLoaded", function() {
    if (window.location.pathname.includes('create_pet_record.php')) {
        document.getElementById('icNo').addEventListener('input', function() {
            var icNoPattern = /^\d{6}-\d{2}-\d{4}$/;
            if(!icNoPattern.test(this.value)) {
                document.getElementById('icNoInvalidFeedback').style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                document.getElementById('icNoInvalidFeedback').style.display = 'none';
                this.classList.remove('is-invalid');
            }
        });
    }
});