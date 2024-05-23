// src/layout/AdminLayout.js
import { Outlet } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import Navbar from '../admin/components/Navbar';
import AdminProvider from '../admin/contexts/AdminContext';

const AdminLayout = () => {



  return (
    <>
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

    </>
  );
}

export default AdminLayout;
