let idb = null;

if (window.indexedDB) {
    idb = {
        open() {
            let db = window.indexedDB.open("strucko", 1);

            db.onupgradeneeded = function(event) {
                // Save the IDBDatabase interface
                let db = event.target.result;
                console.log('IDB needs update to version ' + db.version);
                switch (db.version) {
                    case 1:
                        let objectStore = db.createObjectStore("languages", { keyPath: "id" });
                        objectStore.createIndex('id', 'id', {unique: true});

                        db.createObjectStore("timestamps", {keyPath: "id"});
                }
            };

            db.onerror = function(event) {
                Console.log('Oups, could not use IndexDB');
            };

            return db;
        }
    }
}

export { idb };