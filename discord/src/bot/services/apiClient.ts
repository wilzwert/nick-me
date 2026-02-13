import fetch from 'node-fetch';
import { API_URL, API_KEY } from '../config/dotenv';
import { NickRequestContext } from '../model/NickRequestContext';
import { Nick } from '../model/Nick';

export async function generateNickname(ctx: NickRequestContext): Promise<string> {
    const url = new URL(API_URL);
    url.searchParams.append('offenseLevel', ctx.offense.toString());
    url.searchParams.append('gender', ctx.gender);
    const res = await fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Api-Key': API_KEY
        }
    });

    if (!res.ok) {
        throw new Error(`Erreur API: ${res.status} ${res.statusText}`);
    }
    const nick = (await res.json()) as Nick;
    if (!nick || !nick.words.length) {
        throw new Error('Invalid API response');
    }
    
    return nick.words.map(w => w.label).join(' ');
}
