document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-generuj');
    const bladEl = document.getElementById('blad');
    const wynikEl = document.getElementById('wynik');
    const przycisk = form.querySelector('button[type="submit"]');
    const kopiujBtn = wynikEl.querySelector('[data-kopiuj]');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const dane = new FormData(form);
        dane.set('ajax', '1');

        przycisk.disabled = true;
        przycisk.textContent = '⏳ Generowanie…';
        bladEl.hidden = true;

        try {
            const odp = await fetch(form.action, { method: 'POST', body: dane });
            const wynik = await odp.json();

            if (wynik.ok) {
                wynikEl.hidden = false;
                wynikEl.querySelector('code.pesel').textContent = wynik.pesel;
                odswiezHistorie();
            } else {
                pokazBlad(wynik.blad || 'Wystąpił błąd.');
            }
        } catch (err) {
            pokazBlad('Nie udało się połączyć z serwerem.');
        } finally {
            przycisk.disabled = false;
            przycisk.textContent = '🎲 Generuj PESEL';
        }
    });

    function pokazBlad(tekst) {
        bladEl.textContent = tekst;
        bladEl.hidden = false;
        wynikEl.hidden = true;
    }

    async function odswiezHistorie() {
        const sekcja = document.getElementById('historia');
        try {
            const odp = await fetch('historia.php?ajax=1');
            const html = await odp.text();
            sekcja.innerHTML = '<h2>📜 Historia wygenerowanych numerów</h2>' + html;
        } catch (err) {
            /* historia odświeży się przy następnym generowaniu */
        }
    }

    kopiujBtn.addEventListener('click', async () => {
        const numer = wynikEl.querySelector('code.pesel').textContent.trim();
        if (!numer) return;

        try {
            await navigator.clipboard.writeText(numer);
        } catch (err) {
            // awaryjnie dla starszych przeglądarek / braku HTTPS
            const pole = document.createElement('textarea');
            pole.value = numer;
            document.body.appendChild(pole);
            pole.select();
            document.execCommand('copy');
            pole.remove();
        }

        kopiujBtn.textContent = '✅ Skopiowano';
        setTimeout(() => (kopiujBtn.textContent = '📋 Kopiuj'), 1500);
    });
});
