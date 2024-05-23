


type PaginationTypes = {
  currentPage: number,
  numOfPage: number,
  setCurrentPage:  React.Dispatch<React.SetStateAction<number>>,
}

const Pagination = ({ currentPage, setCurrentPage, numOfPage }: PaginationTypes) => {
  const next = () => {
    if (currentPage < numOfPage) {
      setCurrentPage(prev => prev + 1);
    }
  }

  const prev = () => {
    if (currentPage > 1) {
      setCurrentPage(prev => prev - 1);
    }
  }
  const paginationButtons = () => {
    const buttons = [];
    for (let index = 0; index < numOfPage; index++) {      
      buttons.push(
        <button
          key={index}
          onClick={() => setCurrentPage(index + 1)} 
          className={`${index + 1 === currentPage ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'} shadow-md relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg  text-center align-middle font-sans text-xs font-medium uppercase  shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none`}
          type="button"
        >
          <span className="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
            {index + 1}
          </span>
        </button>
      );
    }
    return buttons
  }
  

  return (
    <div className="flex items-center justify-center gap-4">
      <button
        onClick={prev}
        className="flex items-center gap-2 px-6 py-3 font-sans text-xs font-bold text-center text-gray-900 uppercase align-middle transition-all rounded-lg select-none hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
        type="button"
        disabled={currentPage === 1}
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" aria-hidden="true" className="w-4 h-4">
          <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
        </svg>
        Previous
      </button>
      <div className="flex items-center gap-2">
        {paginationButtons()}
      </div>
      <button
        onClick={next}
        className="flex items-center gap-2 px-6 py-3 font-sans text-xs font-bold text-center text-gray-900 uppercase align-middle transition-all rounded-lg select-none hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
        type="button"
        disabled={currentPage === numOfPage}

      >
        Next
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" aria-hidden="true" className="w-4 h-4">
          <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
        </svg>
      </button>
    </div>
  )
}


export default Pagination