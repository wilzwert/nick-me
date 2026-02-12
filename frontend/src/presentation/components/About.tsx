import { ActionIcon, Modal, Stack } from "@mantine/core";
import { useState } from "react";
import { ContactForm } from "./ContactForm";
import { IconBulb, IconMail } from "@tabler/icons-react";
import { SuggestionForm } from "./SuggestionForm";

export function About() {
    const [contactOpen, setContactOpen] = useState(false);
    const [suggestionOpen, setSuggestionOpen] = useState(false);

    const contact  =<ActionIcon variant="subtle" size="lg" component="a" onClick={() => setContactOpen(true)}>
                  <IconMail size={20} title="Contact"/>
              </ActionIcon>;


    const suggestion  =<ActionIcon variant="subtle" size="lg" component="a" onClick={() => setSuggestionOpen(true)}>
                  <IconBulb size={20} title="Suggérer un mot"/>
              </ActionIcon>;

    

    return (
        <>
            <Stack gap={20}>
            <div>
                <p>NickMe est un générateur de pseudos aléatoires rigolos et parfois offensants, créé avec ❤️.</p>
                <p>Un pseudo est constitué de 2 mots assemblés au hasard selon le genre et le niveau d'offense demandé.</p>
                <p>Pour m'aider à enrichir la liste de mots disponibles, c'est par ici : {suggestion}</p>
            </div>

            <div>Ci-dessous du blabla légal probablement pas très utile ;)</div>

            <div>
                <h2>Mentions légales & Politique de confidentialité</h2>
                <h3>1. Mentions légales</h3>
                <p>Conformément aux dispositions des articles 6-III et 19 de la Loi n°2004-575 du 21 juin 2004 pour la Confiance dans l'Économie Numérique, dite L.C.E.N., nous portons à la connaissance des utilisateurs et visiteurs du site les informations suivantes :</p>

                <h4>Éditeur du site</h4>
                <p>
                Nom : Wilhelm Zwertvaegher
                Statut : Particulier
                Contact : {contact}
                </p>
                <p>
                <h4>Hébergement</h4>
                Hébergeur :Infomaniak
                Adresse : Infomaniak Network SA, Rue Eugène Marziano 25, 1227 Les Acacias (GE), Suisse
                Site web : https://www.infomaniak.com/
                </p>
                <p>
                L'application est fournie à titre gratuit, sans objectif commercial.
                </p>
                <h3>2. Politique de confidentialité</h3>
                <h4>2.1 Responsable du traitement</h4>
                <p>
                Le responsable du traitement des données personnelles est :
                Wilhelm Zwertvaegher, en tant que particulier. {contact}
                </p>
                <h4>2.2 Données collectées</h4>
                <p>
                Les seules données personnelles susceptibles d'être collectées sont :
                </p>
                <p>
                Adresse e-mail, lorsque l'utilisateur remplit l'un des formulaires suivants :
                </p>
                <ul>
                <li>suggestion de nouveau mot,</li>
                <li>signalement d'un pseudo,</li>
                <li>formulaire de contact.</li>
                </ul>
                <p>
                Aucune autre donnée personnelle n'est collectée.
                </p>
                <h4>2.3 Finalités du traitement</h4>
                <p>
                L'adresse e-mail est collectée uniquement afin de :
                </p>
                <ul>
                <li>répondre aux suggestions de nouveaux mots,</li>
                <li>traiter les signalements de pseudos,</li>
                <li>répondre aux messages envoyés via le formulaire de contact.</li>
                </ul>
                <p>
                Elle n'est pas utilisée à d'autres fins.
                </p>
                <h4>2.4 Base légale du traitement</h4>
                <p>
                Le traitement des données repose sur :
                </p>
                <ul>
                <li>le consentement de l'utilisateur, donné volontairement lors de l'envoi du formulaire,</li>
                <li>la nécessité d'exécuter la demande formulée par l'utilisateur.</li>
                </ul>
                <h4>2.5 Destinataires des données</h4>
                <p> 
                Les données collectées sont accessibles uniquement par l'éditeur du site.
                Elles ne sont ni vendues, ni cédées, ni partagées avec des tiers.
                </p>
                <h4>2.6 Durée de conservation</h4>
                <p>
                Les adresses e-mail sont conservées uniquement pendant le temps nécessaire au traitement de la demande, puis supprimées.
                </p>
                <p>
                À titre indicatif : au maximum 6 mois après le dernier échange lié à la demande.
                </p>

                <h4>2.7 Cookies et traceurs</h4>
                <p>
                Le site n'utilise aucun cookie et aucun traceur à des fins de suivi, d'analyse ou de publicité.
                </p>

                <h4>2.8 Droits des utilisateurs</h4>
                <p>
                Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants concernant vos données personnelles :
                </p>
                <ul>
                <li>droit d'accès,</li>
                <li>droit de rectification,</li>
                <li>droit à l'effacement,</li>
                <li>droit d'opposition,</li>
                <li>droit à la limitation du traitement.</li>
                </ul>
                <p>
                Vous pouvez exercer ces droits en utilisant le formulaire de contact {contact}.
                </p>
                <p>
                Vous avez également la possibilité d'introduire une réclamation auprès de l'autorité de contrôle compétente, notamment la CNIL (France).
                </p>
                <p>
                Vous avez également la possibilité d'introduire une réclamation auprès de l'autorité de contrôle compétente, notamment la CNIL (France).
                </p>
                <h4>2.9 Modification de la politique de confidentialité</h4>

                Cette politique de confidentialité peut être modifiée à tout moment afin de rester conforme aux évolutions légales ou fonctionnelles de l'application.
                </div>

            </Stack>
            <Modal opened={contactOpen} onClose={() => setContactOpen(false)} title="Contact">
                <ContactForm onClose={() => setContactOpen(false)} />
            </Modal>
            <Modal opened={suggestionOpen} onClose={() => setSuggestionOpen(false)} title="Suggérer un mot">
                <SuggestionForm onClose={() => setSuggestionOpen(false)} />
            </Modal>
        </>
    )
}