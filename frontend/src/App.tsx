
import './App.css'
import { NickForm } from './presentation/NickForm';

function App() {
  return (
    <div style={{ padding: '2rem', maxWidth: 600, margin: '0 auto' }}>
      <h1>Générateur de pseudonymes</h1>
      <NickForm />
    </div>
  );
}

export default App
