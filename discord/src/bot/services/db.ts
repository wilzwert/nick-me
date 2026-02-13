// db.js
import { Low } from 'lowdb';
import { JSONFile } from 'lowdb/node';

interface Data {
    guilds: Record<string, string>
}

const adapter = new JSONFile<Data>('guildKeys.json');
const initialData: Data = { guilds: {} };
const db = new Low<Data>(adapter, initialData);

export async function initDB() {
  await db.read();
  db.data ||= { guilds: {} };
  await db.write();
}

export default db;
