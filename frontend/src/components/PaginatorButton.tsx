import React, { Dispatch, SetStateAction } from 'react'

type PaginatorButtonTypes = {
  page: number,
  count: number,
  setIsHovered: Dispatch<SetStateAction<boolean>>,
  setPercentage: Dispatch<SetStateAction<number>>,
  setPage: Dispatch<SetStateAction<number>>,

}

const PaginatorButton = ({ page, count, setIsHovered, setPercentage, setPage }: PaginatorButtonTypes) => {
  return (
    <button
      className={`rounded-full py-2 px-4 gap-4 mx-2 ${page === count ? 'bg-white text-black' : 'text-white bg-gray-900'}`}
      onMouseEnter={() => setIsHovered(false)}
      onMouseLeave={() => setIsHovered(true)}
      onClick={() => {
        setPercentage(0);
        setPage(count);
      }}
    > {count} </button>
  )
}

export default PaginatorButton
