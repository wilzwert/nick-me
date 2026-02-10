
import { MantineProvider } from '@mantine/core';
import '@mantine/core/styles.css';
import './App.css'
import { AltchaModal } from './presentation/components/AltchaModal';
import { Nick } from './presentation/components/Nick';
import { NickForm } from './presentation/components/NickForm';
import { NickHistory } from './presentation/components/NickHistory';
import { theme } from './theme';
import { AppLayout } from './presentation/AppLayout';
import { AppTitle } from './presentation/components/AppTitle';
import { ToastContainer } from './presentation/components/ToastContainer';
import { ReportNickModal } from './presentation/components/ReportNickModal';
import { useFavicon } from './hooks/useFavicon';

function App() {

  useFavicon();

  return (
    <MantineProvider theme={theme} defaultColorScheme="auto">
      <AppLayout>  
        <AppTitle />
        <NickForm/>
        <Nick />
        <NickHistory />
        <AltchaModal />
        <ReportNickModal />
        <ToastContainer />
      </AppLayout>
    </MantineProvider>
  );
}

export default App
