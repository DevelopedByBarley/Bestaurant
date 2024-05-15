import { Outlet } from "react-router-dom"
import { ToastContainer } from "react-toastify"
import 'react-toastify/dist/ReactToastify.css';
import Navbar from "../components/Navbar";
//import Footer from "../components/Footer";

const MainLayout = () => {
  return (
    <>
      <Navbar />
      <Outlet />
      {/*  <Footer /> */}
      <ToastContainer
        position="top-center"
        autoClose={2500}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        />
    </>
  )
}

export default MainLayout