import axios from 'axios';
import { useEffect, useState } from 'react'


type CSFRType = {
  dependency: any
}

const CSFR = ({ dependency = null }: CSFRType) => {
  const [token, setToken] = useState('')
  console.log('Hello csfr!');
  useEffect(() => {
    axios.get('/token').then(res => setToken(res.headers['x-csrf-token']))
  }, [dependency]);
  return <input type="hidden" name="csrf" value={token} />

}

export default CSFR
