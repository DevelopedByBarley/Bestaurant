import './App.css'
import { Route, RouterProvider, createBrowserRouter, createRoutesFromElements } from 'react-router-dom';
import MainLayout from './layout/MainLayout';
import Main from './pages/Main';
import Error from './pages/Error';
import { ModalContextProvider } from './context/ModalContext';



const router = createBrowserRouter(
  createRoutesFromElements(<Route element={<MainLayout />} >
    <Route path='/' element={<Main />} />
    <Route path="*" element={<Error />} />
  </Route>)
);

function App() {
  return (
    <ModalContextProvider>
      <RouterProvider router={router} />
    </ModalContextProvider>
  )
}

export default App