import dayjs from 'dayjs'

export const useProjectEvent = (project) => {
  return {
    id: project.id,
    title: project.title,
    start: project.start,
    end: dayjs(project.deadline).add(1, 'day').format('YYYY-MM-DD'),
    url: route('projects.show', { project: project.id }),
    description: project.remarks,
    allDay: true,
    color: '#4f46e5',
    deleted: project.deleted_at || false,
  }
}
