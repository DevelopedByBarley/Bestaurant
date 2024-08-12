
const toastBtn = document.querySelector('.toast-btn');
const root = document.getElementById('toast-root');
const duration = 30;


if (root.children.length > 0) {
  const toastData = JSON.parse(document.getElementById('toast-data').getAttribute('data-toast'));

  toast(toastData.content, toastData.style)
}

toastBtn?.addEventListener('click', () => {
  toast(
    {
      title: null,
      message: null,
      time: new Date().toLocaleTimeString('hu-HU', { hour: 'numeric', minute: 'numeric' })
    },
    {
      textColor: 'white',
      background: 'info'
    }
  );
});


function toast(content, style) {
  renderToasts(root, content, style);
}


function renderToasts(root, content, style) {
  const toast = document.createElement('div');
  toast.classList.add('toast');
  let timer = 5;
  let isPaused = false;

  // Inicializáljuk a percent változót a timer alapján
  const percent = timer * 40 + '%';

  toast.innerHTML = `
    <div class="toast-header d-flex justify-content-between bg-${style.background ? style.background : 'light'} text-${style.textColor ? style.textColor : 'dark'}">
      <strong class="mr-auto">${content.title ? content.title : 'MVC message!'}</strong>
      <small>${content.time ? content.time : 'most'}</small>
    </div>
    <div class="toast-body">
      ${content.message ? content.message : 'Please give message!'}
    </div>
    <div class="timer-line  bg-${style.background ? style.background : 'dark'}" style="height: 4px; width: ${percent}"></div>
  `;

  root.appendChild(toast);

  // Bootstrap toast megjelenítése
  toast.classList.add('show');




  // Automatikus eltávolítás a Bootstrap toast megszámláló lejártakor
  const autoRemoveId = setInterval(function () {
    if (!isPaused) {
      timer -= 0.05;
      const percent = timer * 20 + '%'; // Frissítjük a percent értékét a timer alapján
      toast.querySelector('.timer-line').style.width = percent; // Frissítjük a timer-line stílusát
    }

    if (timer <= .0) {
      toast.animate([
        // key frames
        { transform: 'translateX(200%)' }
      ], {
        // sync options
        duration: 1000,
      })
    }

    if (timer <= -.5) {
      root.removeChild(toast);
      clearInterval(autoRemoveId);
    }
  }, duration);

  toast.addEventListener('mouseover', () => {
    isPaused = true;
  });

  toast.addEventListener('mouseleave', () => {
    isPaused = false;
  });

  toast.addEventListener('click', () => {

    toast.animate([
      // key frames
      { transform: 'translateX(200%)' }
    ], {
      // sync options
      duration: 300,
    })
    setTimeout(() => {
      root.removeChild(toast);
      clearInterval(autoRemoveId)
    }, 300)
  })
}









