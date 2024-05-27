import React from 'react';

type SlideProps = {
  imageUrl: string;
  text: string;
  isActive: boolean;
};

const Slide: React.FC<SlideProps> = React.memo(({ imageUrl, text, isActive }) => {
  return (
    <>
      <div
        className={`absolute inset-0 transition-opacity duration-700 ease-in-out ${isActive ? 'zoom-in-and-out' : 'opacity-0'}`}
        style={{ background: `url(${imageUrl}) top center/cover` }}
      ></div>
      <div className="h-full w-full flex items-center justify-center absolute p-16">
        <h2 className="text-white text-3xl xl:text-7xl font-extrabold text-center text-shadow">{isActive ? text : ''}</h2>
      </div>
    </>
  );
});

export default Slide;
