import { GoArrowDown, GoArrowUp } from "react-icons/go";
import { IoFilterOutline } from "react-icons/io5";
import { ReservationsTableType, ReservationsTypes } from "../../../types/ReservationsTypes";
import { fetchAuthentication } from "../../../services/AuthService";
import { toast } from "react-toastify";
import { useState } from "react";
import Modal from "../../../components/Modal";
import CSFR from "../../../components/CSFR";
import { MoonLoader } from "react-spinners";

const ReservationsTable = ({ reservations, setReservations, sortConfig, requestSort }: ReservationsTableType) => {
  const [show, setShow] = useState(false);
  const [currentReservation, setCurrentReservation] = useState<ReservationsTypes | null>(null);
  const [modalStatus, setModalStatus] = useState<'accept' | 'cancel' | 'delete' | 'show'>();
  const [isButtonPending, setButtonPending] = useState(false);



   const renderArrowBySortDirection = () => {
    if (sortConfig.direction === 'desc') {
      return <GoArrowDown />;
    } else if (sortConfig.direction === 'asc') {
      return <GoArrowUp />;
    } else {
      return <IoFilterOutline />;
    }
  };



  const generateModalContent = (currentReservation: ReservationsTypes | null) => {
    const acceptReservation = (e: React.FormEvent<HTMLFormElement>, id: number) => {
      setButtonPending(true);
      e.preventDefault();

      const elements = e.currentTarget.elements as HTMLFormControlsCollection;

      const accept = {
        csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
      }

      fetchAuthentication.post('/api/reservation/accept/' + id, accept).then((res) => {
        const { message, data } = res.data;
        toast.success(message);
        setReservations((prevReservations) => {
          const next = [...prevReservations];
          const index = prevReservations.findIndex((prev) => prev.id === id);
          next[index].isAccepted = true;
          next[index].admin = data;

          return next;
        });
        // Close the modal after accepting the reservation
      }).catch(() => toast.error('Általános szerver hiba!')).finally(() => {

        setButtonPending(false);
        setShow(false);
      })
    };

    const cancelReservation = (e: React.FormEvent<HTMLFormElement>, id: number) => {
      setButtonPending(true);
      e.preventDefault();

      const elements = e.currentTarget.elements as HTMLFormControlsCollection;

      const cancellation = {
        csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
        message: (elements.namedItem("message") as HTMLInputElement)?.value,
      }

      fetchAuthentication.post(`/api/reservation/cancel/${id}`, cancellation).then(() => {
        setReservations((prevReservations) => {
          const next = [...prevReservations];
          const index = prevReservations.findIndex((prev) => prev.id === id);
          next.splice(index, 1);
          return next;
        });

        toast.success('Foglalás sikeresen visszavonva!');
      }).catch(() => toast.error('Általános szerver hiba!')).finally(() => {
        setButtonPending(false);
        setShow(false);
      })

    }


    const deleteReservation = (e: React.FormEvent<HTMLFormElement>, id: number) => {
      setButtonPending(true);
      e.preventDefault();

      const elements = e.currentTarget.elements as HTMLFormControlsCollection;

      const deleted = {
        csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
      }

      fetchAuthentication.post(`/api/reservation/delete/${id}`, deleted).then(() => {
        setReservations((prevReservations) => {
          const next = [...prevReservations];
          const index = prevReservations.findIndex((prev) => prev.id === id);
          next.splice(index, 1);
          return next;
        });
        toast.success('Foglalás sikeresen törölve!');
      }).catch(() => toast.error('Általános szerver hiba!')).finally(() => {
        setButtonPending(false);
        setShow(false);
      })

    }

    if (!currentReservation) return null;

    switch (modalStatus) {
      case 'accept':
        return (
          <form onSubmit={(e) => acceptReservation(e, currentReservation.id)}>
            <CSFR dependency={currentReservation} />

            <h1 className="text-2xl font-extrabold">Biztosan elfogadja a következő foglalást?</h1>
            <div className="mt-5">
              <h1 className="text-xl">{currentReservation.name}</h1>
              <h1 className="text-xl">{currentReservation.start} - {currentReservation.end}</h1>
            </div>
            <div className="mt-5 space-x-5 flex">
              <button type="submit" disabled={modalStatus === 'accept' && isButtonPending} className={`border ${modalStatus === 'accept' && isButtonPending ? 'bg-teal-600' : ''} btn-green flex items-center justify-center`}>
                {modalStatus === 'accept' && isButtonPending ? <MoonLoader size={20} color="rgba(255, 255, 255, 1)" /> : 'Elfogad'}
              </button>
              <button
                className="btn-dark"
                onClick={() => setShow(false)}>Bezár</button>
            </div>
          </form>
        );
      case 'cancel':
        return (
          <form onSubmit={(e) => cancelReservation(e, currentReservation.id)}>
            <CSFR dependency={currentReservation} />
            <h1 className="text-2xl font-extrabold">Biztosan visszavonja a következő foglalást?</h1>
            <div className="flex items-center bg-rose-500 text-white text-sm font-bold px-4 py-3 my-5" role="alert">
              <svg className="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" /></svg>
              <p>A foglalás visszavonása a foglalás végleges törlését is maga után vonja.</p>
            </div>
            <div className="my-5">
              <label htmlFor="message" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Üzenet a felhasználónak a foglalás lemondásáról.</label>
              <textarea id="message" required name="message" rows={4} className="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Üzenet..."></textarea>
            </div>
            <div className="mt-5">
              <h1 className="text-xl">{currentReservation.name}</h1>
              <h1 className="text-xl">{currentReservation.start} - {currentReservation.end}</h1>
            </div>
            <div className="mt-5 space-x-5 flex">
              <button type="submit" disabled={modalStatus === 'cancel' && isButtonPending} className={`border ${modalStatus === 'cancel' && isButtonPending ? 'bg-teal-600' : ''} btn-green flex items-center justify-center`}>
                {modalStatus === 'cancel' && isButtonPending ? <MoonLoader size={20} color="rgba(255, 255, 255, 1)" /> : 'Visszavonás'}
              </button>
              <button
                className="btn-dark"
                onClick={() => setShow(false)}>Bezár</button>
            </div>
          </form>
        );
      case 'delete':
        return (
          <form onSubmit={(e) => deleteReservation(e, currentReservation.id)}>
            <CSFR dependency={currentReservation} />
            <h1 className="text-2xl font-extrabold">Biztosan törli a következő foglalást?</h1>
            <div className="mt-5">
              <h1 className="text-xl">{currentReservation.name}</h1>
              <h1 className="text-xl">{currentReservation.start} - {currentReservation.end}</h1>
            </div>
            <div className="mt-5 space-x-5 flex">
              <button type="submit" disabled={modalStatus === 'delete' && isButtonPending} className={`border ${modalStatus === 'delete' && isButtonPending ? 'bg-teal-600' : ''} btn-green flex items-center justify-center`}>
                {modalStatus === 'delete' && isButtonPending ? <MoonLoader size={20} color="rgba(255, 255, 255, 1)" /> : 'Elfogad'}
              </button>
              <button
                className="btn-dark"
                onClick={() => setShow(false)}>Bezár</button>
            </div>
          </form>
        );



      case 'show':
        return (
          <div>
            <h1 className="text-2xl font-extrabold">Foglalás részletei</h1>
            <div className="mt-5">
              <h1 className="text-xl">Név: {currentReservation.name}</h1>
              <h1 className="text-xl">Időszak: {currentReservation.start} - {currentReservation.end}</h1>
              <h1 className="text-xl">Státusz: {Boolean(Number(currentReservation.isAccepted)) ? 'Elfogadva' : 'Függőben'}</h1>
            </div>
            <div className="mt-5 space-x-5 flex">
              <button className="btn-green" onClick={() => { setModalStatus('accept') }}>
                Elfogadás
              </button>
              {Boolean(Number(currentReservation.isAccepted)) ? (
                <button
                  className="btn-yellow"
                  onClick={() => {
                    setModalStatus('cancel');
                  }}>
                  Visszavonás
                </button>
              ) : ''}
              <button
                className="btn-red"
                onClick={() => {
                  setModalStatus('delete');
                }}>
                Törlés
              </button>
            </div>
          </div>
        );
      default:
        return null;
    }


  }

  return (
    <div>
      <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col-1" className="px-2 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('id')}>
              <div className="flex items-center justify-center">
                # <span className='text-lg mx-1'>{sortConfig.key === 'id' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
              </div>
            </th>
            <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('name')}>
              <div className="flex items-center justify-center">
                Név <span className='text-lg mx-1'>{sortConfig.key === 'name' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
              </div>
            </th>
            <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('date')}>
              <div className="flex items-center justify-center">
                Dátum <span className='text-lg mx-1'>{sortConfig.key === 'date' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
              </div>
            </th>
            <th scope="col" className="px-16 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('start')}>
              <div className="flex items-center justify-center">
                Érkezés <span className='text-lg mx-1'>{sortConfig.key === 'start' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
              </div>
            </th>
            <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('numOfGuests')}>
              <div className="flex items-center justify-center">
                Vendégek száma <span className='text-lg mx-1'>{sortConfig.key === 'numOfGuests' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
              </div>
            </th>
            <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('isAccepted')}>
              <div className="flex items-center justify-center">
                Elfogadva <span className='text-lg mx-1'>{sortConfig.key === 'isAccepted' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
              </div>
            </th>
            <th scope="col" className="px-6 py-3">Admin ID</th>
            <th scope="col" className="px-6 py-3">Email</th>
            <th scope="col" className="px-6 py-3">Telefonszám</th>
            <th scope="col" className="px-6 py-3">Különleges kérések</th>
            <th scope="col" className="px-6 py-3">Műveletek</th>
          </tr>
        </thead>
        <tbody>
          {reservations.map((reservation) => (
            <tr key={reservation.id} className={`${Boolean(Number(reservation.isAccepted)) ? 'bg-cyan-300 hover:bg-cyan-400 dark:bg-cyan-700 dark:hover:bg-cyan-600' : 'bg-rose-300 hover:bg-rose-400 dark:bg-rose-700 dark:hover:bg-rose-600'} transition`}>
              <th scope="row" className="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white font-extrabold">{reservation.id}</th>
              <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.name}</th>
              <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.date}</th>
              <td className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.start} - {reservation.end}</td>
              <td className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.numOfGuests}</td>
              <td className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{Boolean(Number(reservation.isAccepted)) ? 'Igen' : 'Nem'}</td>
              <td className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.admin}</td>
              <td className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.email}</td>
              <td className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.phone}</td>
              <td className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{reservation.request.length !== 0 ? 'Megadva' : 'Nincs'}</td>
              <td className="px-6 py-4 flex gap-5">
                <button onClick={() => { setCurrentReservation(reservation); setShow(true); setModalStatus('show') }} className="btn-light">
                  Megtekint
                </button>
                {Boolean(Number(reservation.isAccepted)) ? (
                  <div>
                    <button onClick={() => { setCurrentReservation(reservation); setShow(true); setModalStatus('cancel') }} className="btn-yellow">Visszavon</button>
                  </div>
                ) : (
                  <div className="flex space-x-2">
                    <button
                      onClick={() => {
                        setCurrentReservation(reservation);
                        setShow(true);
                        setModalStatus('accept');
                      }}
                      className="btn-green">
                      Elfogad
                    </button>
                    <button onClick={() => { setCurrentReservation(reservation); setShow(true); setModalStatus('delete') }} className="btn-red">Törlés</button>

                  </div>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table >
      <Modal show={show} setShow={setShow} title={Boolean(Number(currentReservation?.isAccepted)) ? 'Foglalás visszavonása' : 'Foglalás elfogadása'}>
        {generateModalContent(currentReservation)}
      </Modal>
    </div >
  );
};

export default ReservationsTable;
