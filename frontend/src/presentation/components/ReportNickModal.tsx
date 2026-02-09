import { Modal } from '@mantine/core';
import { ReportForm } from './ReportForm';
import { useReportNickStore } from '../stores/report-nick.store';


export function ReportNickModal() {
  const nick = useReportNickStore(s => s.nick);
  const setNick = useReportNickStore(s => s.setNick);

  return (
    nick && 
    <Modal opened={nick !== null} onClose={() => setNick(null)} title={`Signaler "${nick.words.map(w => w.label).join(' ')}"`}>
      <ReportForm onClose={ () => {setNick(null);} } nickId={nick.id}/>
    </Modal>
  );
}
