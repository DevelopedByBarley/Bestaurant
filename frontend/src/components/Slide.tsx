import React from 'react';

type SlideProps = {
  page: number;
  count: number
};

const Slide: React.FC<SlideProps> = React.memo(({ page, count}) => {
  const contents = [
    {},
    {
      image: 'restaurant-interior.jpg',
      content: 'JÖJJÖN EL ÉTTERMÜNKBE ÉS GAZDAGODJON ÚJ ÉLMÉNYEKKEL'
    },
    {
      image: 'glasses-red-white-wine.jpg',
      content: 'PRÉMIUM BORLAPUNKRÓL MEGTALÁLHATJA AZ ÖNNEK MEGFELELŐ ITALT'
    },
    {
      image: 'happy-waiters-bringing-food-table-serving-group-friends-restaurant.jpg',
      content: 'MINŐSÉGI KISZOLGÁLÁS'
    },
    {
      image: 'restaurants-terrace-with-black-green-awnings.jpg',
      content: 'KÜLTÉR VAGY BELTÉR IZLÉS SZERINT'
    },
  ]

  return (
    <>

      {page === count && (
        <div
          className={`pt-20 absolute flex items-center justify-center zoom-in-and-out w-full h-full opacity-0`}
          style={{ background: `url('/public/assets/images/${contents[page].image}') top center/cover` }}
        >
          <div className={`${page === count ? 'fade-bottom' : ''} xl:w-4/6`}>
            <h1 className='text-2xl xl:text-7xl font-extrabold text-white text-center text-shadow'>{contents[page].content}</h1>
          </div>
        </div>
      )}
    </>
  );
});

export default Slide;
