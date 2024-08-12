/* Example
  Just use blur load class div with img child. Use loading lazy
  <div class="blur-load">
    <img src="/public/assets/uploads/images/1.png" loading="lazy" alt="">
  </div>
*/


const blurDivs = document.querySelectorAll('.blur-load');

blurDivs.forEach(div => {
  const img = div.querySelector('img');

  const imgUrl = '/public/assets/images/img-placeholder.png';
  div.style.backgroundImage = `url(${imgUrl})`;
  div.style.backgroundPosition = 'center';
  div.style.backgroundSize = 'cover';




  const loaded = () => {
    div.style.backgroundImage  = ''
    div.classList.add('loaded');
  }

  if (img.complete) {
    setTimeout(() => {
      loaded();
    }, 2000);
  } else {
    img.addEventListener('load', loaded);
  }
})
