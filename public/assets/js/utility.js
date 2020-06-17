function storageAvailable(type) {
    let storage;
    try {
        storage = window[type];
        let x = '__storage_test__';
        storage.setItem(x, x);
        storage.removeItem(x);
        return true;
    } catch (e) {
        return e instanceof DOMException && (
                // everything except Firefox
            e.code === 22 ||
            // Firefox
            e.code === 1014 ||
            // test name field too, because code might not be present
            // everything except Firefox
            e.name === 'QuotaExceededError' ||
            // Firefox
            e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
            // acknowledge QuotaExceededError only if there's something already stored
            (storage && storage.length !== 0);
    }
}

function isSignedIn() {
    if (!storageAvailable('localStorage')) {
        // skip checking if storage is not available
        console.log('skipping auth... logged in')
        return true;
    }

    let userStatus = localStorage.getItem('status');
    if (!userStatus) {
        console.log('status: logged out')
        return false;
    }

    if (userStatus === 'loggedIn') {
        console.log('status: logged in')
        return true;
    }

    console.log('status: logged out')
    return false;
}

function signIn() {
    if (storageAvailable('localStorage')) {
        localStorage.setItem('status', 'loggedIn')
        console.log('logging in...')
    }
}

function signOut() {
    localStorage.removeItem('status')
    console.log('logging out...')
}
