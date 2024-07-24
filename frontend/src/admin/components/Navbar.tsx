import React, { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAdminContext } from '../contexts/AdminContext';
import CSFR from '../../components/CSFR';
import { toast } from 'react-toastify';
import { authByToken, fetchAuthentication } from '../../services/AuthService';

const Navbar = () => {
  const navigate = useNavigate();

  const { isLoggedIn, setLoggedIn } = useAdminContext();
  const [isExpanded, setIsExpanded] = useState(false);
  const [adminLevel, setAdminLevel] = useState<number | null>(null);

  const toggleMenu = () => {
    setIsExpanded(!isExpanded);
  };

  useEffect(() => {
    if (isLoggedIn) {
      const admin = authByToken();
      if (admin) {
        setAdminLevel(admin.level);
      }
    }
  }, [isLoggedIn])

  if (!isLoggedIn) {
    return null;
  }

  const logout = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const elements = e.currentTarget.elements as HTMLFormControlsCollection;
    const logoutData = {
      csrf: (elements.namedItem('csrf') as HTMLInputElement)?.value,
    };

    try {
      const res = await fetchAuthentication.post('/api/admin/logout', logoutData);
      const { status } = res.data;

      if (status) {
        localStorage.removeItem('accessToken');
        setLoggedIn(false);
        toast.success('Sikeres kijelentkezés!');
        navigate('/admin');
      }
    } catch (error) {
      console.error('Logout error:', error);
      // Handle logout error
    }
  };

  return (
    <nav className="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
      <div className="max-w-screen-xl flex items-center justify-between mx-auto py-4 px-8">
        <div className="flex items-center">
          <div className="flex items-center space-x-3">
            <span className="text-2xl font-semibold dark:text-white">
              <img className="w-10 h-10 rounded-full" src="/docs/images/people/profile-picture-5.jpg" alt="Rounded avatar" />
            </span>
          </div>
        </div>
        <div className="hidden xl:flex items-center space-x-10">
          {adminLevel && Number(adminLevel) > 1 && (
            <>
              <Link to="/admin/reservations" className="text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Foglalások</Link>
              <Link to="/admin/capacities" className="text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Kapacitás</Link>
              <Link to="/admin/opening" className="text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Nyitvatartás</Link>
              <Link to="/admin/holidays" className="text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Kivételek és ünnepnapok</Link>
              <Link to="/admin/menu" className="text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Étlap és itallap</Link>
              <Link to="/admin/menu" className="text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Üzenetek</Link>
              {Number(adminLevel) === 3 && <Link to="/admin/admin-list" className="text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Adminok</Link>}
            </>
          )}

        </div>
        <div className="flex items-center">
          <form onSubmit={logout} className="mr-4">
            <CSFR dependency={[]} />
            <button type="submit" className="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Kijelentkezés</button>
          </form>
          {adminLevel && Number(adminLevel) > 1 && (
            <button onClick={toggleMenu} className="xl:hidden text-gray-500 focus:outline-none">
              <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                {isExpanded ? (
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                ) : (
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                )}
              </svg>
            </button>
          )}
        </div>
      </div>
      {adminLevel && Number(adminLevel) > 1 && (
        <div className={`${isExpanded ? 'block' : 'hidden'} xl:hidden`}>
          <ul className="px-2 py-3">
            <li><Link to="/admin/reservations" className="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Foglalások</Link></li>
            <li><Link to="/admin/capacities" className="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Kapacitás</Link></li>
            <li><Link to="/admin/opening" className="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Nyitvatartás</Link></li>
            <li><Link to="/admin/holidays" className="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Kivételek és ünnepnapok</Link></li>
            <li><Link to="/admin/menu" className="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Étlap és itallap</Link></li>
            {Number(adminLevel) === 3 && <li><Link to="/admin/admin-list" className="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Adminok</Link></li>}
          </ul>
        </div>
      )}
    </nav >
  );
};

export default Navbar;
