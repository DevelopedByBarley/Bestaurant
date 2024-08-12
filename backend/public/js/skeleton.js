
/**
 * @example: 
 *  -  Create container like <div class="skeleton-template-container"></div>
 *  - Create template like 
 *       <template class="test-template">
 *            <div class="skeleton skeleton-text"></div>
 *      </template>
 * 
 *   use fetchDataWithSkeleton with the container and template
*   @param {string} url - The URL to fetch data from.
*  @param {HTMLElement} template - The template to use for displaying skeletons.
*  @param {number} countOfTemplate - Count of templates what u want to render
*  @param {HTMLElement} container - The container to append the rendered data to.
*  @param {function} renderFunc(url, template, countOfTemplate, container, renderFunc)    - Callback function for rendering template and data
*/





const cardTemplateContainer = document.querySelector('.skeleton-template-container');
const cardTemplate = document.getElementById('card-template');
const testTemplate = document.querySelector('.test-template');

/**
 * Clones and appends skeleton templates to the specified container.
 * 
 * @param {HTMLElement} template - The template to clone.
 * @param {HTMLElement} container - The container to append the clones to.
 * @param {number} count - The number of skeletons to append.
 */
const appendSkeletons = (template, countOfTemplate = 10, container) => {
  if (!template) throw new Error('No template provided for skeleton');
  if (!container) throw new Error('No container provided for skeleton');

  for (let i = 0; i < countOfTemplate; i++) {
    container.append(template.content.cloneNode(true));
  }
};

/**
 * Fetches data from the specified URL and renders it using the provided render function.
 * 
 * @param {string} url - The URL to fetch data from.
 * @param {HTMLElement} template - The template to use for displaying skeletons.
 * @param {HTMLElement} container - The container to append the rendered data to.
 * @param {function} renderFunc - The function to use for rendering fetched data.
 */
const fetchDataWithSkeleton = (url, template, countOfTemplate, container, renderFunc) => {
  appendSkeletons(template, countOfTemplate, container);

  setTimeout(() => {
    fetch(url)
      .then(res => res.json())
      .then(posts => {
        container.innerHTML = '';  // Clear skeletons
        const renderedContent = renderFunc(posts);
        container.innerHTML = renderedContent;
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, 1000)
};

/**
 * Default render function for posts.
 * 
 * @param {Array} posts - The posts to render.
 * @returns {string} The rendered HTML content.
 */


function defaultRenderTemplate(posts) {
  return posts.map(post => `
    <div class="card skeleton-card mt-5">
      <div class="header">
        <img src="/public/assets/uploads/images/1.png" class="header-img skeleton"></img>
        <div class="title" data-title>
          <div class="">
            <h3>${post.title}</h3>
          </div>
        </div>
      </div>
      <div data-body>
        <p>${post.body}</p>
      </div>
    </div>
  `).join('');
}

// Example usage with default render function
fetchDataWithSkeleton("https://jsonplaceholder.typicode.com/posts", testTemplate, 10, cardTemplateContainer, defaultRenderTemplate);





// Example usage with a custom render function
/*
const customRenderTemplate = (posts) => {
  return posts.map(post => `
    <div class="custom-card">
      <h2>${post.title}</h2>
      <p>${post.body}</p>
    </div>
  `).join('');
};

fetchDataWithSkeleton("https://jsonplaceholder.typicode.com/posts", testTemplate, 10, cardTemplateContainer, customRenderTemplate);
*/
