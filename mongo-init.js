db.createUser(
    {
        user: "root",
        pwd: "",
        roles: [
            {
                role: "readWrite",
                db: "server-management"
            }
        ]
    }
);
