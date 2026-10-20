document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-generuj');
    const bladEl = document.getElementById('blad');
    const wynikEl = document.getElementById('wynik');
    const przycisk = form.querySelector('button[type="submit"]');
    const kopiujBtn = wynikEl.querySelector('[data-kopiuj]');
    const listaPanel = document.getElementById('lista-panel');

    /* ---------- przewijalne listy podpowiedzi ---------- */

    const NAZWY_MIESIECY = ['', 'styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec',
                            'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień'];

    const listy = {
        dzien:   { od: 1, do: 31, etykiety: (n) => String(n) },
        miesiac: { od: 1, do: 12, etykiety: (n) => String(n) + ' — ' + NAZWY_MIESIECY[n] },
        rok:     { od: 1900, do: 2299, etykiety: (n) => String(n) }
    };

    let poleAktywne = null;

    Object.keys(listy).forEach((nazwa) => {
        const pole = form.elements[nazwa];
        if (!pole) return;

        pole.addEventListener('focus', () => otworzListe(pole, nazwa));
        pole.addEventListener('click', () => otworzListe(pole, nazwa));
        pole.addEventListener('input', () => podswietl(pole.value));
        pole.addEventListener('blur', () => setTimeout(zamknijListe, 150));
    });

    function otworzListe(pole, nazwa) {
        const cfg = listy[nazwa];
        poleAktywne = pole;

        listaPanel.innerHTML = '';
        const lista = document.createElement('div');
        lista.className = 'lista-opcji';

        for (let i = cfg.od; i <= cfg.do; i++) {
            const opcja = document.createElement('button');
            opcja.type = 'button';
            opcja.className = 'opcja';
            opcja.dataset.wartosc = i;
            opcja.textContent = cfg.etykiety(i);
            opcja.addEventListener('mousedown', (e) => {
                e.preventDefault();
                pole.value = i;
                ukryjBlad();
                zamknijListe();
                pole.focus();
            });
            lista.appendChild(opcja);
        }

        listaPanel.appendChild(lista);

        const r = pole.getBoundingClientRect();
        listaPanel.style.left = (r.left + window.scrollX) + 'px';
        listaPanel.style.top = (r.bottom + window.scrollY + 6) + 'px';
        listaPanel.style.width = r.width + 'px';
        listaPanel.hidden = false;

        podswietl(pole.value);

        const zaznaczona = lista.querySelector('.opcja.zaznaczona');
        if (zaznaczona) lista.scrollTop = zaznaczona.offsetTop - lista.clientHeight / 2 + zaznaczona.clientHeight / 2;
    }

    function podswietl(wartosc) {
        if (listaPanel.hidden) return;
        const v = parseInt(wartosc, 10);
        listaPanel.querySelectorAll('.opcja').forEach((op) => {
            op.classList.toggle('zaznaczona', parseInt(op.dataset.wartosc, 10) === v);
        });
    }

    function zamknijListe() {
        listaPanel.hidden = true;
        poleAktywne = null;
    }

    document.addEventListener('mousedown', (e) => {
        if (!listaPanel.hidden && !listaPanel.contains(e.target) && e.target !== poleAktywne) {
            zamknijListe();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') zamknijListe();
    });

    window.addEventListener('resize', zamknijListe);

    // komunikat błędu znika, gdy użytkownik poprawia dane
    form.addEventListener('input', ukryjBlad);

    function ukryjBlad() {
        if (!bladEl.hidden) {
            bladEl.hidden = true;
            bladEl.textContent = '';
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // walidacja HTML5 (zakresy pól liczbowych) przed wysłaniem AJAX
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

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
