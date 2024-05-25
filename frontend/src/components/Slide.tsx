import React from 'react';

type SlideProps = {
  page: number;
};

const Slide: React.FC<SlideProps> = React.memo(({ page }) => {


  return (
    <>
      <div
        className={`pt-20 absolute flex items-center justify-center w-full h-full opacity-0 ${page === 1 ? 'zoom-in-and-out' : ''}`}
        style={{ background: `url('/public/assets/images/restaurant-interior.jpg') top center/cover` }}
      >
        <div className={`${page === 1 ? 'fade-bottom' : ''} xl:w-4/6`}>
          <h1 className='text-2xl xl:text-7xl font-extrabold text-white text-center text-shadow'>JÖJJÖN EL ÉTTERMÜNKBE ÉS GAZDAGODJON ÚJ ÉLMÉNYEKKEL</h1>
        </div>
      </div>
      <div
        className={`pt-20 absolute flex items-center justify-center w-full h-full opacity-0 ${page === 2 ? 'zoom-in-and-out' : ''}`}
        style={{ background: `url('/public/assets/images/glasses-red-white-wine.jpg') top center/cover` }}
      >
        <div className={`${page === 1 ? 'fade-bottom' : ''} xl:w-4/6`}>
          <h1 className='text-2xl xl:text-7xl font-extrabold text-white text-center text-shadow'>PRÉMIUM BORLAPUNKRÓL MEGTALÁLHATJA AZ ÖNNEK MEGFELELŐ ITALT</h1>
        </div>
      </div>
      <div
        className={`pt-20 absolute flex items-center justify-center w-full h-full opacity-0 ${page === 3 ? 'zoom-in-and-out' : ''}`}
        style={{ background: `url('/public/assets/images/happy-waiters-bringing-food-table-serving-group-friends-restaurant.jpg') top center/cover` }}
      >
        <div className={`${page === 1 ? 'fade-bottom' : ''} xl:w-4/6`}>
          <h1 className='text-2xl xl:text-7xl font-extrabold text-white text-center text-shadow'>MINŐSÉGI KISZOLGÁLÁS</h1>
        </div>
      </div>
      <div
        className={`pt-20 absolute flex items-center justify-center w-full h-full opacity-0 ${page === 4 ? 'zoom-in-and-out' : ''}`}
        style={{ background: `url('/public/assets/images/restaurants-terrace-with-black-green-awnings.jpg') top center/cover` }}
      >
        <div className={`${page === 1 ? 'fade-bottom' : ''} xl:w-4/6`}>
          <h1 className='text-2xl xl:text-7xl font-extrabold text-white text-center text-shadow'>KÜLTÉR VAGY BELTÉR IZLÉS SZERINT</h1>
        </div>
      </div>
    </>
  );
});

export default Slide;
