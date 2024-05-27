import React, { useState, useEffect, useCallback } from 'react';
import Slide from './Slide';

const Carousel = () => {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [timer, setTimer] = useState(0); // Időzítő értéke
  const slidesData = [
    {
      imageUrl: "/public/assets/images/restaurant-interior.jpg",
      text: "JÖJJÖN EL ÉTTERMÜNKBE ÉS GAZDAGODJON ÚJ ÉLMÉNYEKKEL",

    },
    {
      imageUrl: "/public/assets/images/glasses-red-white-wine.jpg",
      text: "PRÉMIUM BORLAPUNKRÓL MEGTALÁLHATJA AZ ÖNNEK MEGFELELŐ ITALT",
    },
    {
      imageUrl: "/public/assets/images/happy-waiters-bringing-food-table-serving-group-friends-restaurant.jpg",
      text: "MINŐSÉGI KISZOLGÁLÁS",
    },
    {
      imageUrl: "/public/assets/images/restaurants-terrace-with-black-green-awnings.jpg",
      text: "KÜLTÉR VAGY BELTÉR IZLÉS SZERINT",
    },
  ];

  const handleNext = useCallback(() => {
    setCurrentSlide((prev) => (prev + 1) % slidesData.length);
  }, [slidesData.length]);

  const handlePrev = useCallback(() => {
    setCurrentSlide((prev) => (prev - 1 + slidesData.length) % slidesData.length);
  }, [slidesData.length]);

  useEffect(() => {
    const interval = setInterval(() => {
      handleNext();
    }, 10000); // 10 másodperc

    // Időzítő értékének beállítása 10000 ms-ra
    setTimer(10000);

    return () => clearInterval(interval); // Időzítő törlése a komponens elhagyásakor
  }, [handleNext, currentSlide]);

  useEffect(() => {
    // Az időzítő csökkentése minden 1000 ms-ban
    const countdown = setInterval(() => {
      setTimer((prev) => prev - 10);
    }, 10);

    return () => clearInterval(countdown); // Törlés a komponens elhagyásakor
  }, []);

  // Progressz bar százalékának kiszámítása
  const progressPercentage = ((10000 - timer) / 10000) * 100;

  return (
    <div className="carousel relative w-full h-screen overflow-hidden bg-gray-900" onClick={() => {
      if (window.innerWidth < 1200) {
        handleNext();
      }
    }}>
      <div className="absolute inset-0">
        {slidesData.map((slide, index) => (
          <Slide
            key={index}
            imageUrl={slide.imageUrl}
            text={slide.text}
            isActive={currentSlide === index}
          />
        ))}
      </div>

      <div className='flex items-center justify-center bg-red-400 w-screen'>
        <div className="absolute bottom-16  h-2 bg-white w-4/5 lg:w-3/5 xl:w-1/5">
          <div
            className="h-full bg-slate-900"
            style={{ width: `${progressPercentage}%` }}
          ></div>
        </div>
      </div>

      <div className="absolute z-30 hidden xl:flex -translate-x-1/2 bottom-5 left-1/2 space-x-3">
        {slidesData.map((_, index) => (
          <button
            key={index}
            type="button"
            className={`w-3 h-3 rounded-full ${currentSlide === index ? 'bg-white' : 'bg-gray-400'}`}
            onClick={() => setCurrentSlide(index)}
            aria-label={`Slide ${index + 1}`}
          ></button>
        ))}
      </div>

      <button
        type="button"
        className="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        onClick={handlePrev}
      >
        <span className="hidden xl:inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
          <svg
            className="w-4 h-4 text-white"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span className="sr-only">Previous</span>
        </span>
      </button>
      <button
        type="button"
        className="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        onClick={handleNext}
      >
        <span className="hidden xl:inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
          <svg
            className="w-4 h-4 text-white"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"></path>
          </svg>
          <span className="sr-only">Next</span>
        </span>
      </button>
    </div >
  );
};

export default Carousel;

