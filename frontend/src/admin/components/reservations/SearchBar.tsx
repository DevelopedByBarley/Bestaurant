import { ChangeEventHandler, Dispatch, SetStateAction, useState } from 'react';

type SearchBarType = {
  category: string,
  setCategory: Dispatch<SetStateAction<string>>,
  search: string,
  setSearch: Dispatch<SetStateAction<string>>
};

const SearchBar = ({ category, setCategory, search, setSearch }: SearchBarType) => {
  const [dropdownVisible, setDropdownVisible] = useState(false);

  const toggleDropdown = () => {
    setDropdownVisible(!dropdownVisible);
  };

  const categoryLabels: { [key: string]: string } = {
    '': 'Választ',
    'name': 'Név',
    'email': 'Email cím',
    'phone': 'Telefonszám'
  };

  const handleCategoryClick = (selectedCategory: string) => {
    let categoryValue = '';
    switch (selectedCategory) {
      case 'Név':
        categoryValue = 'name';
        break;
      case 'Email':
        categoryValue = 'email';
        break;
      case 'Telefonszám':
        categoryValue = 'phone';
        break;
      default:
        categoryValue = '';
        break;
    }

    setCategory(categoryValue);
    setDropdownVisible(false);
  };

  const handleSearchChange: ChangeEventHandler<HTMLInputElement> = (event) => {
    setSearch(event.target.value);
  };

  return (
    <form className="w-screen lg:w-3/6 lg:mt-0 ">
      <div className="flex">
        <label htmlFor="search-dropdown" className="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <button
          id="dropdown-button"
          type="button"
          className="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
          onClick={toggleDropdown}
        >
          {categoryLabels[category] || 'Választ'}
          <svg className="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M1 1l4 4 4-4" />
          </svg>
        </button>
        {dropdownVisible && (
          <div id="dropdown" className="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 absolute mt-12">
            <ul className="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
              <li>
                <button type="button" onClick={() => handleCategoryClick('Név')} className="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Név</button>
              </li>
              <li>
                <button type="button" onClick={() => handleCategoryClick('Email')} className="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Email cím</button>
              </li>
              <li>
                <button type="button" onClick={() => handleCategoryClick('Telefonszám')} className="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Telefonszám</button>
              </li>
            </ul>
          </div>
        )}
        <div className="relative w-full">
          <input
            type="search"
            id="search-dropdown"
            onChange={handleSearchChange}
            disabled={category === ''}
            value={search}
            className={`block p-2.5 w-full z-20 text-sm text-gray-900 ${category === '' ? 'bg-gray-400' : 'bg-gray-50'} rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500`}
            placeholder="Keresés..."
            required
          />
          {search !== '' && (
            <button
              disabled={category === ''}
              onClick={() => {
                setCategory('');
                setSearch('');
              }}
              className="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-gray-700 rounded-e-lg border border-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
            >
              x
              <span className="sr-only">Search</span>
            </button>
          )}
        </div>
      </div>
    </form>
  );
};

export default SearchBar;
