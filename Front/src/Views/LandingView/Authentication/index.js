import React from 'react';
import { GoogleLogin } from '@react-oauth/google';
import axios from 'axios';

function Authentication() {
  const handleLogin = async (response) => {
    try {
      const { credential } = response;

      const res = await axios.post('/auth/google/callback', { credential });

      console.log(res.data);
    } catch (error) {
      console.error('Authentication failed:', error);
    }
  };

  return (
    <GoogleLogin
      text="signin_with"
      shape="rectangular"
      theme="filled_black"
      locale="english"
      onSuccess={handleLogin}
      onError={() => {
        console.log('Login Failed');
      }}
    />
  );
}

export default Authentication;
