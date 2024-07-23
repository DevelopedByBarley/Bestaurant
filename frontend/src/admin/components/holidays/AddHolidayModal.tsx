import React, { useState } from 'react';
import Modal from '../../../components/Modal';
import { fetchAuthentication } from '../../../services/AuthService';
import { toast } from 'react-toastify';
import Datepicker, { DateValueType } from 'react-tailwindcss-datepicker';

type HolidayModalType = {
    show: boolean;
    setShow: (show: boolean) => void;
};

export const AddHolidayModal = ({ show, setShow }: HolidayModalType) => {
    const [isHoliday, setIsHoliday] = useState(0);
    const [calendar, setCalendar] = useState<DateValueType>({
        startDate: null,
        endDate: null
    });

    const handleCalendarChange = (dates: DateValueType | null) => {
        setCalendar(dates);
    };


    const handleSelectChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
        setIsHoliday(Number(event.target.value));
    };

    const handleSubscription = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        const elements = e.currentTarget.elements as HTMLFormControlsCollection;


        const newHoliday = {
            csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
            date: calendar?.startDate,
            open: (elements.namedItem("open") as HTMLInputElement)?.value,
            close: (elements.namedItem("close") as HTMLInputElement)?.value,
            isHoliday: (elements.namedItem("isHoliday") as HTMLInputElement)?.value,
            description: (elements.namedItem("description") as HTMLInputElement)?.value,
        }


        if(!newHoliday.date) {
            return toast.warning('Dátum megadása kötelező.')
        }

        if ((newHoliday.open && newHoliday.close) && newHoliday.open > newHoliday.close) {
            return toast.warning('A kivétel nyitása nem lehet nagyobb vagy egyenlő mint a zárás')
        }


        fetchAuthentication.post(`/api/holidays`, newHoliday).then(res => {
            console.log(res.data);
            toast.success('Soron következő kapacitás sikeresen frissítve!');
            setShow(false);
        }).catch((err) => {
            console.error(err);
            toast.error('Általános szerver hiba!');
        })
    }

    return (
        <Modal show={show} setShow={setShow} title='Kivétel hozzáadása'>
            <form onSubmit={handleSubscription}>
                <div className='mb-4'>
                    <label className='block text-gray-700 text-sm font-bold mb-2' htmlFor='date'>
                        Dátum
                    </label>
                    <Datepicker useRange={false} asSingle={true} value={calendar} minDate={new Date(new Date().setDate(new Date().getDate() + 1))} onChange={handleCalendarChange} />
                </div>

                <div className='mb-4'>
                    <label className='block text-gray-700 text-sm font-bold mb-2' htmlFor='isHoliday'>
                        Ünnepnap
                    </label>
                    <select
                        id='isHoliday'
                        name='isHoliday'
                        value={isHoliday}
                        onChange={handleSelectChange}
                        className='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'
                    >
                        <option value={0}>Nem ünnepnap</option>
                        <option value={1}>Ünnepnap</option>
                    </select>
                </div>

                <div className="flex gap-3">
                    <div className='mb-4 w-3/6'>
                        <label className='block text-gray-700 text-sm font-bold mb-2' htmlFor='open'>
                            Nyitás
                        </label>
                        <input
                            type='time'
                            id='open'
                            name='open'
                            disabled={isHoliday === 1}
                            className='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'
                        />
                    </div>

                    <div className='mb-4 w-3/6'>
                        <label className='block text-gray-700 text-sm font-bold mb-2' htmlFor='close'>
                            Zárás
                        </label>
                        <input
                            type='time'
                            id='close'
                            name='close'
                            disabled={isHoliday === 1}
                            className='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'
                        />
                    </div>
                </div>

                <div className='mb-4'>
                    <label className='block text-gray-700 text-sm font-bold mb-2' htmlFor='description'>
                        Leírás
                    </label>
                    <textarea
                        id='description'
                        name='description'
                        rows={3}
                        className='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'
                    ></textarea>
                </div>

                <div className='flex items-center justify-between'>
                    <button
                        type='submit'
                        className='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
                    >
                        Hozzáadás
                    </button>
                </div>
            </form>
        </Modal>
    );
};

export default AddHolidayModal;
