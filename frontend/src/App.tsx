
import './App.css'
import { Nick } from './presentation/Nick';
import { NickForm } from './presentation/NickForm';
import { NickHistory } from './presentation/NickHistory';

function App() {
  return (
    <div style={{ padding: '2rem', maxWidth: 600, margin: '0 auto' }}>
      <h1>Générateur de pseudonymes</h1>
      <NickForm/>
      <Nick />
      <NickHistory />
    </div>
  );
}

export default App
