document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').onsubmit = function() {

        fetch('https://api.exchangerate-api.com/v4/latest/USD')
        
        .then(response => response.json())
        .then(data => {
            const currency = document.querySelector('#currency').value.toUpperCase();
            const rate = data.rates[currency];

            if (rate !== undefined) {
                document.querySelector('#result').innerHTML = `1 USD is equal to ${rate.toFixed(3)} ${currency}`;
            } 
            else {
                document.querySelector('#result').innerHTML = 'Invalid currency code';
            }
        })
        .catch(error => {
            console.error('Error fetching exchange rate:', error);
            document.querySelector('#result').innerHTML = 'Failed to fetch exchange rate.';
        });

        return false; // Prevent form from submitting normally
    };
});