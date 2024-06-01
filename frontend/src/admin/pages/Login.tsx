import React, { useEffect, useRef } from 'react'
import { fetchAuthentication } from '../../services/AuthService'
import CSFR from '../../components/CSFR'
import { useNavigate } from 'react-router-dom'
import { toast } from 'react-toastify'
import { useAdminContext } from '../contexts/AdminContext'

const Login = () => {

  const navigate = useNavigate();
  const nameRef = useRef<HTMLInputElement>(null)
  const passwordRef = useRef<HTMLInputElement>(null)
  const { setLoggedIn } = useAdminContext();

  useEffect(() => {
    if (localStorage.getItem('accessToken') && localStorage.getItem('accessToken') !== 'undefined') navigate('/admin/reservations');
  }, [navigate])


  const loginAdmin = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const elements = e.currentTarget.elements as HTMLFormControlsCollection;

    const admin = {
      name: nameRef.current?.value,
      password: passwordRef.current?.value,
      csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
    }


    fetchAuthentication.post('/api/admin/login', admin).then(res => {
      localStorage.setItem('accessToken', res.data.accessToken);
      toast.success('Sikeres bejelentkezés!')
      setLoggedIn(true);
      navigate('/admin/reservations');
    })
      .catch((err) => {
        const { response } = err
        
        toast.error(response.data.message);
        navigate('/admin');
        console.error(err);
        return;
      })
  }

  return (
    <section className="bg-gray-50 dark:bg-gray-900" >
      <div className="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div className="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">

        </div>
        <div className="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div className="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 className="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
              Sign in to your account
            </h1>
            <form className="space-y-4 md:space-y-6" onSubmit={loginAdmin}>
              <CSFR dependency={''} />
              <div>
                <label htmlFor="name" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                <input ref={nameRef} type="text" name="name" id="name" className="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" />
              </div>
              <div>
                <label htmlFor="password" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input ref={passwordRef} type="password" name="password" id="password" placeholder="••••••••" className="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>
              <button type='submit' className="bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 px-4 border border-gray-500 hover:border-transparent rounded">
                Belépés
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  )
}

export default Login
