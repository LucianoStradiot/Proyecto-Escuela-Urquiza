import React, { useEffect, useState } from 'react';
import styles from './header.module.css';
import { Link } from 'react-router-dom';
import { useModalContext, useStateContext } from '../Contexts';
import axiosClient from '../Shared/Axios';
import { FaBell } from 'react-icons/fa';
import { BiBell } from 'react-icons/bi';

const Header = () => {
  const { user, token, setUserHeader, notification, updateNotification } = useStateContext();
  const { openModal, closeModal } = useModalContext();
  const [hasPendingNotifications, setHasPendingNotifications] = useState(false);
  const [isNotificationOpen, setisNotificationOpen] = useState(false);
  const [useNotification, setUseNotification] = useState(false);
  const [students, setStudents] = useState([]);

  const handleNotificationIconClick = () => {
    setisNotificationOpen(!isNotificationOpen);
    if (isNotificationOpen) {
      setUseNotification(false);
    }
    setUseNotification(true);
  };

  const fetchNotifications = async () => {
    try {
      const { data } = await axiosClient.get('/super-admin/administration');
      const newNotifications = data.data;

      setStudents(newNotifications);

      if (newNotifications.length > 0) {
        const hasShownModalBefore = sessionStorage.getItem('hasShownNotificationModal');
        if (!hasShownModalBefore) {
          openModal({
            title: 'Hola!',
            description: 'Tenés notificaciones nuevas',
            confirmBtn: 'Aceptar',
            confirmModal: true,
            onClick: () => {
              closeModal();
              sessionStorage.setItem('hasShownNotificationModal', 'true');
            }
          });
        }
      }

      setHasPendingNotifications(newNotifications.length > 0);
    } catch (error) {
      console.error('error');
    }
  };

  const fetchData = async () => {
    try {
      const newNotifications = await fetchNotifications();
      updateNotification(newNotifications);
      setUserHeader(user, token);
    } catch (error) {
      throw new Error();
    }
  };

  useEffect(() => {
    if (sessionStorage.getItem('role') === 'SA') {
      fetchData();
    }
  }, [user, token, notification]);

  return sessionStorage.getItem('role') === 'DS' ? (
    <>
      <header className={styles.header}>
        <div className={`${styles.container} ${styles.containerDs}`}>
          <h1 className={styles.title}>Escuela Superior de Comercio N°49</h1>
        </div>
        <div className={`${styles.container2} ${styles.containerDs}`}>
          <div className={styles.photoContainer}>
            <img
              src={
                user.profile_photo || `${process.env.PUBLIC_URL}/assets/images/defaultProfile.png`
              }
              className={styles.profilePhoto}
            />
          </div>
          <div className={styles.namesTitle}>{user.name}</div>
        </div>
        <div className={styles.wallpaper}></div>
      </header>
    </>
  ) : sessionStorage.getItem('role') === 'AF' ? (
    <>
      <header className={styles.header}>
        <div className={`${styles.container} ${styles.containerAf}`}>
          <h1 className={styles.title}>Escuela Superior de Comercio N°49</h1>
        </div>
        <div className={`${styles.container2} ${styles.containerAf}`}>
          <div className={styles.photoContainer}>
            <img
              src={
                user.profile_photo || `${process.env.PUBLIC_URL}/assets/images/defaultProfile.png`
              }
              className={styles.profilePhoto}
            />
          </div>
          <div className={styles.namesTitle}>{user.name}</div>
        </div>
        <div className={styles.wallpaper}></div>
      </header>
    </>
  ) : sessionStorage.getItem('role') === 'ITI' ? (
    <>
      <header className={styles.header}>
        <div className={`${styles.container} ${styles.containerIti}`}>
          <h1 className={styles.title}>Escuela Superior de Comercio N°49</h1>
        </div>
        <div className={`${styles.container2} ${styles.containerIti}`}>
          <div className={styles.photoContainer}>
            <img
              src={
                user.profile_photo || `${process.env.PUBLIC_URL}/assets/images/defaultProfile.png`
              }
              className={styles.profilePhoto}
            />
          </div>
          <div className={styles.namesTitle}>{user.name}</div>
        </div>
        <div className={styles.wallpaper}></div>
      </header>
    </>
  ) : sessionStorage.getItem('role') === 'SA' ? (
    <>
      <header className={styles.header}>
        <div className={styles.container}>
          <h1 className={`${styles.title} ${styles.titleLanding}`}>
            Escuela Superior de Comercio N°49
          </h1>
        </div>
        <div className={styles.container2}>
          <div className={`${styles.namesTitle} ${styles.titleLanding}`}>
            Bienvenido Super Admin
          </div>
        </div>
        <div
          className={styles.notificationContainerPrincipal}
          onClick={handleNotificationIconClick}
        >
          {hasPendingNotifications ? (
            <div
              className={`${styles.notificationContainer} ${
                isNotificationOpen ? styles.scrollableNotifications : ''
              }`}
            >
              {isNotificationOpen ? (
                <BiBell className={styles.notificationIcon} />
              ) : (
                <FaBell className={styles.notificationIcon} />
              )}
              {isNotificationOpen ? (
                <>
                  <div className={styles.notificationPopup}>
                    <h2>Notificaciones Pendientes</h2>
                    <ul className={styles.ulNotifications}>
                      {students && students.length > 0
                        ? students.map((notification) => (
                            <Link to="/super-admin/administracion" key={notification.id}>
                              <li className={styles.liNotifications}>
                                <div className={styles.photoContainer}>
                                  <img
                                    src={
                                      notification.profile_photo ||
                                      `${process.env.PUBLIC_URL}/assets/images/defaultProfile.png`
                                    }
                                    className={styles.profilePhoto}
                                    alt="Profile"
                                  />
                                </div>
                                <div>{notification.name} quiere ingresar al sistema.</div>
                              </li>
                            </Link>
                          ))
                        : null}
                    </ul>
                  </div>
                </>
              ) : !useNotification ? (
                <div className={styles.notificationCount}>{students.length}</div>
              ) : null}
            </div>
          ) : (
            <div className={styles.notificationContainer} onClick={handleNotificationIconClick}>
              <FaBell className={styles.notificationIcon} />
              {isNotificationOpen ? (
                <div className={styles.notificationPopup}>
                  <h2>Notificaciones Pendientes</h2>
                  <ul className={styles.ulNotifications}>
                    <li>No hay notificaciones.</li>
                  </ul>
                </div>
              ) : null}
            </div>
          )}
        </div>
        <div className={styles.wallpaper}></div>
      </header>
    </>
  ) : (
    <>
      <header className={styles.header}>
        <div className={styles.container}>
          <h1 className={`${styles.title} ${styles.titleLanding}`}>Escuela de Comercio N°49</h1>
        </div>
        <div className={styles.wallpaper}></div>
      </header>
    </>
  );
};

export default Header;
