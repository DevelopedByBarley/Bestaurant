import React, { useEffect, useState } from 'react'
import { authByToken } from '../../services/AuthService';
import Error from '../../pages/Error';

const AdminList = () => {
  const [adminLevel, setAdminLevel] = useState<number | null>(null);

  useEffect(() => {

    const admin = authByToken();
    if (admin) {
      setAdminLevel(admin.level)
    }
  }, [])
  


  return (
    <>
      {adminLevel !== 3 ? <Error /> : (
        <h1>Adminok listája</h1>
      )}


    </>
  )
}

export default AdminList
