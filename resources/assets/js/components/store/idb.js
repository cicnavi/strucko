// let idb be null for start.
let idb = null;

// If indexedDB is available, we will make new idb instance.
if (window.indexedDB) {
    idb = {
        /**
         * Open IDB database.
         *
         * @returns {IDBOpenDBRequest}
         */
        open() {
            let db = window.indexedDB.open("strucko", 1);

            // The structure of the database. Update as needed.
            db.onupgradeneeded = function(event) {
                // Save the IDBDatabase interface
                let db = event.target.result;
                console.log('IDB needs update to version ' + db.version);
                switch (db.version) {
                    case 1:
                        // Languages store will keep all languages available in the app.
                        let languagesStore = db.createObjectStore("languages", { keyPath: "id" });
                        languagesStore.createIndex('id', 'id', {unique: true});
                        languagesStore.createIndex('ref_name', 'ref_name', {unique: true});
                        // Timestamps will store time when the particular object store has been updated.
                        let timestampsStore = db.createObjectStore("timestamps", {keyPath: "id"});
                }
            };

            db.onerror = function(event) {
                console.log('Oups, could not use IndexDB');
            };

            return db;
        }
    }
}

export { idb };