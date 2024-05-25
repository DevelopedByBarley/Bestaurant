import  { useContext } from 'react'
import Reservation from './Reservation';
import { ModalContext } from '../context/ModalContext';
import Header from '../components/Header';

const Main = () => {

  const { modal } = useContext(ModalContext);





  return (
    <div className='relative'>
      <div className="h-screen">
        {modal && <Reservation />}
        <section className={modal ? 'overflow-hidden' : ''}>
          <Header />
        </section>
      </div>
      <div className="h-screen" id='story'>
          
      </div>
    </div>
  )
}

export default Main
