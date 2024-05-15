import React, { useContext, useEffect } from 'react'
import axios from 'axios';
import Reservation from './Reservation';
import { ModalContext } from '../context/ModalContext';

const Main = () => {

  const { modal } = useContext(ModalContext);

  useEffect(() => {
    axios.get('/token').then(res => console.log(res.data));
  }, [])

  console.log(modal);




  return (
    <>
      <div className="bg-red-100 h-screen">
        {modal && <Reservation />}
      </div>
      <div className="h-screen"></div>
    </>
  )
}

export default Main
