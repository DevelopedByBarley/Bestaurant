const cardTemplateContainer = document.querySelector('.card-template-container')
const cardTemplate = document.getElementById('card-template')

const fetchingCardData = (url, template, container) => {
  if (container) {
    for (let i = 0; i < 10; i++) {
      container.append(template.content.cloneNode(true));
    }


    fetch(url)
      .then(res => res.json())
      .then(posts => {
        container.innerHTML = ''
        let temp = ``;
        posts.forEach(post => {
          console.log(post);
          temp += `
      <div class="card skeleton-card mt-5">
        <div class="header">
          <img src="https://picsum.photos/200/300" class="header-img skeleton"></img>
          <div class="title" data-title>
            <div class="">
              <h3>
              ${post.title}
              </h3>
            </div>
          </div>
        </div>
        <div data-body>
          <p>${post.body}</p>
        </div>
      </div>
      `;


          container.innerHTML = temp;
        })
      })
  }
}

fetchingCardData("https://jsonplaceholder.typicode.com/posts", cardTemplate, cardTemplateContainer);