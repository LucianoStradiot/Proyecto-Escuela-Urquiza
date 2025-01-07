import React, { useState, useRef } from 'react';
import styles from './login.module.css';
import { Link, useNavigate } from 'react-router-dom';
import Aside from '../../../Components/Shared/Aside';
import TextInput from '../../../Components/Shared/TextInput';
import Button from '../../../Components/Shared/Button';
import Modal from '../../../Components/Shared/Modal';
import Spinner from '../../../Components/Shared/Spinner';
import axiosClient from '../../../Components/Shared/Axios';
import { FaEyeSlash, FaEye } from 'react-icons/fa';
import { useStateContext, useModalContext } from '../../../Components/Contexts';
import Authentication from '../Authentication';

const Login = () => {
  const { openModal, modalState, closeModal } = useModalContext();
  const { setUser, setTokenAndRole } = useStateContext();
  const [isLoading, setIsLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const navigate = useNavigate();
  const emailRef = useRef();
  const passwordRef = useRef();
  const [errors, setErrors] = useState({
    email: null,
    password: null
  });

  const onSubmit = async (e) => {
    e.preventDefault();
    const payload = {
      email: emailRef.current.value,
      password: passwordRef.current.value
    };
    setIsLoading(true);
    setErrors({});
    try {
      const { data } = await axiosClient.post('/login', payload);
      if (data.user.career === 'SA') {
        setUser(data.user);
        setTokenAndRole(data.token, data.user.career);
        openModal({
          description: 'Sesión iniciada correctamente',
          chooseModal: false
        });
        navigate('/super-admin');
      } else {
        setUser(data.user);
        setTokenAndRole(data.token, data.user.career);
        openModal({
          description: 'Sesión iniciada correctamente',
          chooseModal: false
        });
        navigate(`/alumno/profile/${data.user.id}`);
      }
    } catch (err) {
      if (err.response && err.response.status === 422) {
        const apiErrors = err.response;

        if (apiErrors.data.errors) {
          setErrors({
            email: apiErrors.data.errors.email?.[0],
            password: apiErrors.data.errors.password?.[0]
          });
        } else if (apiErrors.data.messageEmail) {
          setErrors({
            email: [apiErrors.data.messageEmail]
          });
        } else {
          setErrors({
            password: [apiErrors.data.messagePassword]
          });
        }
        if (apiErrors.data.messageVerification) {
          openModal({
            title: 'Advertencia',
            description: [apiErrors.data.messageVerification],
            confirmBtn: 'Aceptar',
            onClick: closeModal,
            noButton: false,
            confirmModal: true
          });
        }
      }
    }
    setIsLoading(false);
  };

  return (
    <>
      {isLoading && <Spinner />}
      <Aside page={'home'} />
      {modalState.isOpen && modalState.chooseModal === false ? (
        <Modal />
      ) : modalState.isOpen && modalState.confirmModal === true ? (
        <Modal />
      ) : null}
      <main>
        <section className={styles.container}>
          <div className={styles.subContainer}>
            <form className={styles.loginContainer}>
              <TextInput
                input={'input'}
                labelName={'E-mail'}
                placeholderText={'Escribe tu dirección de correo electrónico'}
                refrerence={emailRef}
                error={errors.email}
              />
              <div className={styles.passwordContainer}>
                <TextInput
                  labelName={'Contraseña'}
                  placeholderText={'Escribe tu contraseña'}
                  input={'input'}
                  refrerence={passwordRef}
                  error={errors.password}
                  inputType={showPassword ? 'text' : 'password'}
                />
                {showPassword ? (
                  <FaEye
                    className={styles.showPasswordIcon}
                    onClick={() => setShowPassword(!showPassword)}
                  />
                ) : (
                  <FaEyeSlash
                    className={styles.showPasswordIcon}
                    onClick={() => setShowPassword(!showPassword)}
                  />
                )}
              </div>
              <Authentication />
              <Link to="/signup" className={styles.password}>
                <p>No tenés una cuenta? Registrate</p>
              </Link>
              <Link to="/recover-password" className={styles.password}>
                <p>Olvidaste tu contraseña?</p>
              </Link>
              <div className={styles.btnContainer}>
                <Button type="submit" text="Enviar" onClick={onSubmit} />
              </div>
            </form>
          </div>
        </section>
      </main>
    </>
  );
};

export default Login;
