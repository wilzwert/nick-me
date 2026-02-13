import fetch, { Response } from 'node-fetch';
import { API_BASE_URL, INTERNAL_APP_KEY } from '../config/dotenv';
import { NickRequestContext } from '../model/NickRequestContext';
import { Nick } from '../model/Nick';
import { ApiKey } from '../model/ApiKey';
import { logger } from './logger';

const fetchFromApi = function(url: URL, method: string, apiKey? :string): Promise<Response> {
    console.log('INTERNAL_APP_KEY', INTERNAL_APP_KEY)

    const headers: Record<string, string> = {
        'Content-Type': 'application/json',
        'X-Internal-App-Key': INTERNAL_APP_KEY,
    };

    if (apiKey) {
        headers['X-Api-Key'] = apiKey;
    }

    return fetch(url, {
        method: method,
        headers
    })
}

export async function generateNickname(ctx: NickRequestContext): Promise<string> {
    const url = new URL(API_BASE_URL+'/nick');
    url.searchParams.append('offenseLevel', ctx.offense.toString());
    url.searchParams.append('gender', ctx.gender);
    const res = await fetchFromApi(url, 'GET', ctx.apiKey);
    if (!res.ok) {
        throw new Error(`Erreur API: ${res.status} ${res.statusText}`);
    }
    const nick = (await res.json()) as Nick;
    if (!nick || !nick.words.length) {
        throw new Error('Invalid API response');
    }
    return nick.words.map(w => w.label).join(' ');
}

export async function requestApiKey(): Promise<string> {
    const url = new URL(API_BASE_URL+'/key');

    logger.info({event: 'request_api_key', message: 'Requestin with ', url})

    const res = await fetchFromApi(url, 'POST');
    if (!res.ok) {
        throw new Error(`Erreur API: ${res.status} ${res.statusText}`);
    }
    const key = (await res.json()) as ApiKey;
    if (!key || !key.key) {
        throw new Error('Invalid API response');
    }
    
    return key.key;
}
