import React, { useState, useEffect } from 'react';
import Cursor from './Cursor';
import PaginatorButton from './PaginatorButton';
import Progress from './ProgressBar';
import Slide from './Slide';
import ToBottom from './ToBottom';


const Header = () => {
  const [page, setPage] = useState(1);
  const [isHovered, setIsHovered] = useState(false);
  const [percentage, setPercentage] = useState(0);
  const [cursorPosition, setCursorPosition] = useState({ x: 0, y: 0 });

  const handleMouseEnter = () => {
    setIsHovered(true);
  };

  const handleMouseLeave = () => {
    setIsHovered(false);
  };

  const handleMouseMove = (e: React.MouseEvent) => {
    const rect = e.currentTarget.getBoundingClientRect();
    setCursorPosition({
      x: e.clientX - rect.left - 25,
      y: e.clientY - rect.top - 25
    });
  };

  useEffect(() => {
    const calculatePaginateInterval = () => {
      const max = 10000;
      const duration = 50;
      const percent = 100 / 10000 * duration;

      return setInterval(() => {
        if (percentage === 100) {
          setPercentage(0);
          if (page < 4) {
            setPage(prev => prev + 1);
          } else {
            setPage(1);
          }
        }
        if (percent <= max) setPercentage(prev => prev + percent);
      }, duration);
    };

    const intervalId = calculatePaginateInterval();

    return () => clearInterval(intervalId);
  }, [page, percentage]);


  const handlePageChange = (e: React.MouseEvent) => {
    if (page < 4) {
      setPercentage(0)
      setPage(prev => prev + 1)
    } else if (page === 4) {
      setPage(1)
      setPercentage(0)
    } else {
      setPage(1)
    }
  };

  return (
    <div className='relative z-30 h-screen w-screen overflow-hidden bg-gray-900' id='header' onMouseEnter={handleMouseEnter} onMouseLeave={handleMouseLeave} onMouseMove={handleMouseMove} style={{ cursor: isHovered ? 'none' : 'auto' }}>
      <div className={`h-screen w-full absolute`}>
        <div className='absolute bottom-0 left-0 xl:right-16 xl:left-auto right-0 xl:w-1/5 z-30 p-5 gap-5 h-32 flex justify-center'>
          <div>
            {[1, 2, 3, 4].map((count) => (
              <PaginatorButton
                key={`button-${count}`}
                page={page}
                count={count}
                setIsHovered={setIsHovered}
                setPercentage={setPercentage}
                setPage={setPage} />
            ))}
          </div>
          <Progress percentage={percentage} />
        </div>
      </div>

      <div onClick={handlePageChange}>
        {[1, 2, 3, 4].map((count) => (
          <Slide page={page} count={count} />
        ))}
      </div>

      {isHovered && <Cursor cursorPosition={cursorPosition} percentage={percentage} />}
      <ToBottom />

    </div >
  );
};

export default Header;
