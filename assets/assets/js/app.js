document.addEventListener('DOMContentLoaded', () => {
    const phone = document.querySelector('input[name="phone"]');

    if (phone) {
        phone.addEventListener('input', () => {
            let digits = phone.value.replace(/\D/g, '');

            if (digits.startsWith('7')) {
                digits = '8' + digits.slice(1);
            }
            if (!digits.startsWith('8')) {
                digits = '8' + digits;
            }

            digits = digits.slice(0, 11);

            let result = '8';
            if (digits.length > 1) result += '(' + digits.slice(1, 4);
            if (digits.length >= 4) result += ')';
            if (digits.length > 4) result += digits.slice(4, 7);
            if (digits.length > 7) result += '-' + digits.slice(7, 9);
            if (digits.length > 9) result += '-' + digits.slice(9, 11);

            phone.value = result;
        });
    }

    document.querySelectorAll('.card').forEach((card, index) => {
        card.style.animationDelay = `${index * 45}ms`;
        card.classList.add('card-enter');
    });
});
