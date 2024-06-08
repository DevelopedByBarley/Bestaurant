import { IoFilterOutline } from 'react-icons/io5';
import { GoArrowDown, GoArrowUp } from 'react-icons/go';
import { Dispatch, SetStateAction, useEffect, useState } from 'react';
import Modal from '../../../components/Modal';
import Datepicker, { DateValueType } from 'react-tailwindcss-datepicker';
import { fetchAuthentication } from '../../../services/AuthService';
import { MoonLoader } from 'react-spinners';
import CSFR from '../../../components/CSFR';
import { toast } from 'react-toastify';
import EmptyAlertHeader from '../../../components/EmptyAlertHeader';


type CapacitiesTableTypes = {
    capacities: CapacityTypes[],
    setCapacities: Dispatch<SetStateAction<CapacityTypes[]>>
    sortConfig: {
        key: keyof CapacityTypes | null;
        direction: "asc" | "desc" | null | '';
    }
    requestSort: (key: keyof CapacityTypes) => void
    show: boolean,
    setShow: Dispatch<SetStateAction<boolean>>;
    modalStatus: string,
    setModalStatus: Dispatch<SetStateAction<'default' | 'exception' | 'update' | 'delete' | ''>>
}

export const CapacitiesTable = ({ capacities, setCapacities, sortConfig, requestSort, show, setShow, modalStatus, setModalStatus }: CapacitiesTableTypes) => {
    const [buttonLoading, setButtonLoading] = useState(false);
    const [currentCapacity, setCurrentCapacity] = useState<CapacityTypes | null>(null);
    const [calendar, setCalendar] = useState<DateValueType>({
        startDate: null,
        endDate: null
    });


    useEffect(() => {
        const date = currentCapacity?.date ? currentCapacity.date : null;
        setCalendar({
            startDate: date,
            endDate: date
        });
    }, [currentCapacity])

    const renderArrowBySortDirection = () => {
        if (sortConfig.direction === 'desc') {
            return <GoArrowDown />;
        } else if (sortConfig.direction === 'asc') {
            return <GoArrowUp />;
        } else {
            return <IoFilterOutline />;
        }
    };

    const handleCalendarChange = (dates: DateValueType | null) => {
        setCalendar(dates);
    }

    const deleteCapacityException = (e: React.FormEvent<HTMLFormElement>) => {

        e.preventDefault();

        const elements = e.currentTarget.elements as HTMLFormControlsCollection;

        const deleted = {
            csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
        }


        fetchAuthentication.post(`/api/capacity/delete/${currentCapacity ? currentCapacity.id : ''}`, deleted).then(res => {
            setButtonLoading(true);
            const { deletedId } = res.data;

            setCapacities(prevCapacities => prevCapacities.filter(capacity => Number(capacity.id) !== deletedId));
            setShow(false);
            toast.success('Kivétel törlése sikeres!')
        }).catch(err => {
            toast.error('Általános szerver hiba.')
            console.error("Error deleting capacity:", err);
        }).finally(() => setButtonLoading(false))
    }

    const updateCapacityException = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const elements = e.currentTarget.elements as HTMLFormControlsCollection;

        const updated = {
            csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
            date: calendar?.startDate,
            capacity: (elements.namedItem("capacity") as HTMLInputElement)?.value,
        }

        fetchAuthentication.post(`/api/capacity/update/${currentCapacity ? currentCapacity.id : ''}`, updated).then(res => {
            const { updated } = res.data;

            setCapacities((prevCapacity) => {
                const next = [...prevCapacity];
                const index = next.findIndex(capacity => capacity.id === updated.id);
                next[index] = updated;
                return next;
            })

            toast.success('Kivétel frissítése sikeres!')

        }).catch(err => {
            console.error(err)
            toast.error('Általános szerver hiba.')
        }).finally(() => { 
            setShow(false);
        })
    }

    return (
        <>
            {capacities.length === 0 ? <EmptyAlertHeader message='Jelenleg nincs egyetlen kapacitás sem' /> : (
                <div>

                    {modalStatus === 'update' && (
                        <Modal show={show} setShow={setShow} title='Kivétel frissítése'>
                            <form className="max-w-sm mx-auto pb-10" onSubmit={(e) => updateCapacityException(e)}>
                                <CSFR dependency={currentCapacity} />

                                <label htmlFor="number-input" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-5">Új dátum kiválasztása</label>
                                <div className='border rounded'>
                                    <Datepicker
                                        useRange={false}
                                        asSingle={true}
                                        value={calendar}
                                        onChange={handleCalendarChange}
                                        minDate={new Date()}
                                    />
                                </div>

                                <label htmlFor="number-input" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-6">Új kapacitás</label>
                                <input
                                    name='capacity'
                                    type="number"
                                    id="number-input"
                                    defaultValue={currentCapacity ? currentCapacity.capacity : 0}
                                    aria-describedby="helper-text-explanation"
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="90210"
                                    required
                                />

                                <button className='btn-green mt-5' >Frissítés</button>
                            </form>
                        </Modal>
                    )}
                    {modalStatus === 'delete' && (
                        <Modal show={show} setShow={setShow} title='Kivétel törlése'>
                            <form className='py-5' onSubmit={e => deleteCapacityException(e)}>
                                <CSFR dependency={currentCapacity} />
                                <div className="p-4 md:p-5 text-center">
                                    <svg className="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 className="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Biztosan törlöd ezt a kapacitást a listából?</h3>
                                    <div className="flex items-center justify-center">
                                        <button data-modal-hide="popup-modal" type="submit" className="btn-red  flex items-center justify-center">
                                            {buttonLoading ? <MoonLoader size={20} color="rgba(255, 255, 255, 1)" /> : 'Megerősít'}
                                        </button>
                                        <button onClick={(() => setShow(false))} data-modal-hide="popup-modal" type="button" className="btn-light">Kilépés</button>
                                    </div>
                                </div>
                            </form>
                        </Modal>
                    )}
                    <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-5">
                        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col-1" className="px-2 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('id')}>
                                    <div className="flex items-center justify-center">
                                        # <span className='text-lg mx-1'>{sortConfig.key === 'id' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                                    </div>
                                </th>
                                <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('date')}>
                                    <div className="flex items-center justify-center">
                                        Dátum <span className='text-lg mx-1'>{sortConfig.key === 'date' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                                    </div>
                                </th>
                                <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('capacity')}>
                                    <div className="flex items-center justify-center">
                                        Kapacitás <span className='text-lg mx-1'>{sortConfig.key === 'capacity' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                                    </div>
                                </th>
                                <th scope="col" className="px-16 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('created_at')}>
                                    <div className="flex items-center justify-center">
                                        Létrehozva <span className='text-lg mx-1'>{sortConfig.key === 'created_at' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                                    </div>
                                </th>
                                <th scope="col" className="px-16 py-3 hover:cursor-pointer hover:text-cyan-400">
                                    <div className="flex items-center justify-center">
                                        Műveletek <span className='text-lg mx-1'></span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {capacities.map((capacity) => (
                                <tr key={capacity.id} className={`bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 transition text-center`}>
                                    <th scope="row" className="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white font-extrabold">{capacity.id}</th>
                                    <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{capacity.date}</th>
                                    <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{capacity.capacity}</th>
                                    <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{capacity.created_at}</th>
                                    <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">
                                        <button className="btn-yellow" onClick={() => {
                                            setCurrentCapacity(capacity)
                                            setModalStatus('update');
                                            setShow(true);
                                        }}>
                                            Frissítés
                                        </button>
                                        <button className="btn-red" onClick={() => {
                                            setCurrentCapacity(capacity)
                                            setModalStatus('delete');
                                            setShow(true);
                                        }}>
                                            Törlés
                                        </button>
                                    </th>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
        </>
    );
}
export type CapacityTypes = {
    capacity: number,
    created_at: string,
    date: string,
    id: number,
}
