import { useContext } from 'react';
import Reservation from './Reservation';
import { ModalContext } from '../context/ModalContext';
import Carousel from '../components/Carousel';

const Main = () => {
  const { modal } = useContext(ModalContext);

  return (
    <div className='relative'>
      <div className={modal ? 'fixed md:static inset-0 overflow-hidden' : ''}>
        {modal && <Reservation />}
        <section>
          <Carousel />
        </section>
      </div>
      <div className="h-screen" id='story'>
      </div>
    </div>
  );
};

export default Main;
