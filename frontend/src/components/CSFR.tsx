import axios from 'axios';
import { useEffect, useState } from 'react'

const CSFR = () => {
  const [token, setToken] = useState('')
  useEffect(() => {
    axios.get('/token').then(res => setToken(res.headers['x-csrf-token']))
  }, []);
  return (
    <div>
      <input type="hidden" name="csrf" value={token} />
    </div>
  )
}

export default CSFR
