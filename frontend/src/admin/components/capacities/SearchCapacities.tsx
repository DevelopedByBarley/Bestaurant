
type SearchCapacitiesTypes = {
    search: string,
    setSearch: React.Dispatch<React.SetStateAction<string>>,
}

export const SearchCapacities = ({ search, setSearch }: SearchCapacitiesTypes) => {
    return (
        <form className="w-screen lg:w-2/6 lg:mt-0 ">
            <div className="flex">

                <div className="relative w-full">
                    <input
                        type="search"
                        id="search-dropdown"
                        onChange={(e) => setSearch(e.target.value)}
                        value={search}
                        className={`block p-2.5 w-full z-20 text-sm text-gray-900 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500`}
                        placeholder="Keresés dátumokban..."
                        required
                    />
                    <button
                        onClick={(e) => {
                            e.preventDefault();
                            setSearch('');
                        }}
                        className="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-gray-700 rounded-e-lg border border-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                    >
                        x
                        <span className="sr-only">Search</span>
                    </button>
                </div>
            </div>
        </form>
    )
}
