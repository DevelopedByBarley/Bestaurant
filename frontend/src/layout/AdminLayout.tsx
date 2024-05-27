// src/layout/AdminLayout.js
import { Outlet } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import Navbar from '../admin/components/Navbar';
import AdminProvider from '../admin/contexts/AdminContext';

const AdminLayout = () => {



  return (
    <div className="bg-white dark:bg-gray-700 text-black dark:text-white min-h-screen">
      <AdminProvider>
        <Navbar />
        <Outlet />
        <ToastContainer
          autoClose={2500}
          closeOnClick
          rtl={false}
          pauseOnFocusLoss
          draggable
          pauseOnHover
        />
      </AdminProvider>

    </div>
  );
}

export default AdminLayout;
