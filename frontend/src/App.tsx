
import './App.css'
import { AltchaModal } from './presentation/components/AltchaModal';
import { Nick } from './presentation/components/Nick';
import { NickForm } from './presentation/components/NickForm';
import { NickHistory } from './presentation/components/NickHistory';

function App() {
  return (
    <div style={{ padding: '2rem', maxWidth: 600, margin: '0 auto' }}>
      <h1>Générateur de pseudonymes</h1>
      <NickForm/>
      <Nick />
      <NickHistory />
      <AltchaModal />
    </div>
  );
}

export default App
